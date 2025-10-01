<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        // Check if user is already authenticated
        if (Auth::check()) {
            return $this->redirectAfterAuthentication();
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // Rate limiting
        $key = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])->withInput($request->except('password'));
        }

        // Validate credentials
        $credentials = $this->validateLogin($request);

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            // Log successful login
            $this->logUserActivity(Auth::user(), 'login', $request);

            // Check user status and permissions using Gates
            if (Gate::denies('access-system', Auth::user())) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki akses ke sistem ini.',
                ]);
            }

            if (Gate::denies('account-active', Auth::user())) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda sedang tidak aktif. Hubungi administrator.',
                ]);
            }

            return $this->redirectAfterAuthentication();
        }

        // Failed login attempt
        RateLimiter::hit($key, 300); // 5 minutes lockout after 5 attempts

        return back()->withErrors([
            'email' => 'Email/username atau password salah.',
        ])->withInput($request->except('password'));
    }

    /**
     * Display the registration form.
     */
    public function showRegister(): View
    {
        // Check if registration is allowed using Gate
        if (Gate::denies('register-account')) {
            abort(403, 'Registrasi akun tidak diizinkan saat ini.');
        }

        return view('auth.register');
    }

    /**
     * Handle a registration attempt.
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if registration is allowed
        if (Gate::denies('register-account')) {
            return back()->withErrors([
                'registration' => 'Registrasi akun tidak diizinkan saat ini.',
            ]);
        }

        // Rate limiting for registration
        $key = 'register:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'registration' => "Terlalu banyak percobaan registrasi. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Validate registration data
        $validated = $this->validateRegistration($request);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Default role
            'is_active' => false, // Pending approval
            'email_verified_at' => null,
            'registered_at' => now(),
            'registered_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Fire registered event
        event(new Registered($user));

        // Log registration
        $this->logUserActivity($user, 'register', $request);

        RateLimiter::clear($key);

        return redirect()->route('login')->with('success', 
            'Registrasi berhasil! Akun Anda sedang menunggu persetujuan administrator.'
        );
    }

    /**
     * Display the forgot password form.
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        // Rate limiting for password reset
        $key = 'password-reset:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan reset password. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
        ]);

        // Check if user account is active using Gate
        $user = User::where('email', $request->email)->first();
        if ($user && Gate::forUser($user)->denies('account-active', $user)) {
            return back()->withErrors([
                'email' => 'Akun dengan email ini tidak aktif.',
            ]);
        }

        // Send password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            RateLimiter::hit($key, 300); // 5 minutes cooldown
            
            // Log password reset request
            if ($user) {
                $this->logUserActivity($user, 'password_reset_request', $request);
            }

            return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
        }

        return back()->withErrors([
            'email' => 'Gagal mengirim link reset password.',
        ]);
    }

    /**
     * Display the reset password form.
     */
    public function showResetPassword(Request $request): View
    {
        return view('auth.reset-password', [
            'request' => $request
        ]);
    }

    /**
     * Handle the password reset.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required' => 'Token reset password diperlukan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'password_changed_at' => now(),
                ])->save();

                // Log password reset
                $this->logUserActivity($user, 'password_reset', $request);

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 
                'Password berhasil direset. Silakan login dengan password baru Anda.'
            );
        }

        return back()->withErrors([
            'email' => 'Token reset password tidak valid atau sudah expired.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Log logout activity
        if (Auth::check()) {
            $this->logUserActivity(Auth::user(), 'logout', $request);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Validate login credentials.
     */
    protected function validateLogin(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $loginField = $this->getLoginField($request->email);
        
        return [
            $loginField => $request->email,
            'password' => $request->password,
        ];
    }

    /**
     * Validate registration data.
     */
    protected function validateRegistration(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);
    }

    /**
     * Determine login field (email or username).
     */
    protected function getLoginField(string $login): string
    {
        return filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    /**
     * Redirect after successful authentication.
     */
    protected function redirectAfterAuthentication(): RedirectResponse
    {
        $user = Auth::user();

        // Use Gates to determine redirect destination
        if (Gate::allows('access-admin-panel', $user)) {
            return redirect()->intended(route('backend.dashboard'));
        }

        // For regular users, redirect to user profile or home
        if (Gate::allows('access-user-dashboard', $user)) {
            return redirect()->intended(route('user.profile'));
        }

        // Default redirect to home
        return redirect()->intended(route('home'));
    }

    /**
     * Log user activity.
     */
    protected function logUserActivity(User $user, string $action, Request $request): void
    {
        // Check if activity logging is allowed using Gate
        if (Gate::allows('log-user-activity')) {
            // You can implement activity logging here
            // For example, create an ActivityLog model and store:
            /*
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
            */
            
            // For now, we'll use Laravel's built-in logging
            \Log::info("User {$action}", [
                'user_id' => $user->id,
                'email' => $user->email,
                'action' => $action,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
    }

    /**
     * Get user profile for authenticated user.
     */
    public function profile(): View
    {
        $user = Auth::user();

        // Check if user can view their own profile
        if (Gate::denies('view-profile', $user)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil.');
        }

        return view('auth.profile', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Check if user can update their own profile
        if (Gate::denies('update-profile', $user)) {
            return back()->withErrors([
                'profile' => 'Anda tidak memiliki akses untuk mengubah profil.',
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/', 'unique:users,phone,'.$user->id],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'phone.unique' => 'Nomor telepon sudah digunakan.',
            'current_password.required_with' => 'Password saat ini diperlukan untuk mengubah password.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // If changing password, verify current password
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || 
                !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini tidak benar.',
                ]);
            }
            
            $validated['password'] = Hash::make($validated['password']);
            $validated['password_changed_at'] = now();
        } else {
            unset($validated['password'], $validated['current_password']);
        }

        $user->update($validated);

        // Log profile update
        $this->logUserActivity($user, 'profile_update', $request);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Check if user can change their password
        if (Gate::denies('change-password', $user)) {
            return back()->withErrors([
                'password' => 'Anda tidak memiliki akses untuk mengubah password.',
            ]);
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak benar.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        // Log password change
        $this->logUserActivity($user, 'password_change', $request);

        return back()->with('success', 'Password berhasil diubah.');
    }
}