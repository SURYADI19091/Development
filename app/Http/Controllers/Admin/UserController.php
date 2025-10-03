<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        if (!Gate::allows('manage-users')) {
            abort(403, 'Unauthorized to manage users');
        }

        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get stats for the page
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        if ($request->ajax()) {
            return view('backend.pages.users.partials.table', compact('users'))->render();
        }

        return view('backend.pages.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        if (!Gate::allows('create-user')) {
            abort(403, 'Unauthorized to create users');
        }

        return view('backend.pages.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create-user')) {
            abort(403, 'Unauthorized to create users');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,admin,operator,user',
            'status' => 'required|string|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'email_verified' => 'nullable|boolean',
            'send_welcome_email' => 'nullable|boolean',
        ]);

        // Check role assignment permissions
        if (!$this->canAssignRole($validated['role'])) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to assign this role'
            ], 403);
        }

        try {
            // Handle avatar upload
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'],
                'avatar' => $avatarPath,
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
                'email_verified_at' => $validated['email_verified'] ? now() : null,
                'created_by' => Auth::id(),
            ]);

            // Send welcome email if requested
            if ($validated['send_welcome_email'] ?? false) {
                try {
                    // Mail::to($user->email)->send(new WelcomeEmail($user, $validated['password']));
                } catch (\Exception $e) {
                    \Log::error('Welcome email failed: ' . $e->getMessage());
                }
            }

            // Log activity
            Log::info('User created', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'created_by' => Auth::user()->id,
                'created_by_name' => Auth::user()->name
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'redirect' => route('backend.users.show', $user)
                ]);
            }

            return redirect()->route('backend.users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user: ' . $e->getMessage()
                ]);
            }

            return back()->withInput()
                ->with('error', 'Failed to create user');
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        if (!Gate::allows('view-user', $user)) {
            abort(403, 'Unauthorized to view this user');
        }

        // Get user activity logs if authorized
        $activities = [];
        if (Gate::allows('view-user-activities')) {
            // $activities = Activity::where('causer_id', $user->id)->latest()->take(10)->get();
        }

        return view('backend.pages.users.show', compact('user', 'activities'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        if (!Gate::allows('edit-user', $user)) {
            abort(403, 'Unauthorized to edit this user');
        }

        return view('backend.pages.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        if (!Gate::allows('edit-user', $user)) {
            abort(403, 'Unauthorized to edit this user');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:super_admin,admin,operator,user',
            'status' => 'required|string|in:active,inactive,suspended',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
        ]);

        // Check role assignment permissions
        if ($user->role !== $validated['role'] && !$this->canAssignRole($validated['role'])) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to assign this role'
            ], 403);
        }

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            // Handle password update
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $validated['updated_by'] = Auth::id();

            $user->update($validated);

            // Log activity
            Log::info('User updated', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'updated_by' => Auth::user()->id,
                'updated_by_name' => Auth::user()->name
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully'
                ]);
            }

            return redirect()->route('backend.users.show', $user)
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            \Log::error('User update error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user'
                ]);
            }

            return back()->withInput()
                ->with('error', 'Failed to update user');
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        if (!Gate::allows('delete-user', $user)) {
            abort(403, 'Unauthorized to delete this user');
        }

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account'
            ]);
        }

        try {
            // Delete avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Soft delete or hard delete based on policy
            $userName = $user->name;
            $user->delete();

            // Log activity
            Log::info('User deleted', [
                'user_name' => $userName,
                'deleted_by' => Auth::user()->id,
                'deleted_by_name' => Auth::user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('User deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user'
            ]);
        }
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, User $user)
    {
        if (!Gate::allows('manage-user-status', $user)) {
            abort(403, 'Unauthorized to manage user status');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:active,inactive,suspended'
        ]);

        try {
            $oldStatus = $user->status;
            $user->update([
                'status' => $validated['status'],
                'updated_by' => Auth::id()
            ]);

            // Log activity
            Log::info('User status changed', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'changed_by' => Auth::user()->id,
                'changed_by_name' => Auth::user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('User status update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status'
            ]);
        }
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request)
    {
        if (!Gate::allows('manage-users')) {
            abort(403, 'Unauthorized to perform bulk actions');
        }

        $validated = $request->validate([
            'action' => 'required|string|in:activate,deactivate,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $users = User::whereIn('id', $validated['user_ids'])
                ->where('id', '!=', Auth::id()) // Prevent self-action
                ->get();

            $count = 0;
            foreach ($users as $user) {
                switch ($validated['action']) {
                    case 'activate':
                        if (Gate::allows('manage-user-status', $user)) {
                            $user->update(['status' => 'active']);
                            $count++;
                        }
                        break;
                    
                    case 'deactivate':
                        if (Gate::allows('manage-user-status', $user)) {
                            $user->update(['status' => 'inactive']);
                            $count++;
                        }
                        break;
                    
                    case 'delete':
                        if (Gate::allows('delete-user', $user)) {
                            if ($user->avatar) {
                                Storage::disk('public')->delete($user->avatar);
                            }
                            $user->delete();
                            $count++;
                        }
                        break;
                }
            }

            // Log activity
            Log::info('Bulk action performed', [
                'action' => $validated['action'],
                'users_count' => $count,
                'performed_by' => Auth::user()->id,
                'performed_by_name' => Auth::user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => "Bulk action completed successfully on {$count} users"
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk action error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action'
            ]);
        }
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        if (!Gate::allows('export-users')) {
            abort(403, 'Unauthorized to export users');
        }

        try {
            $query = User::query();

            // Apply filters from request
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('role')) {
                $query->where('role', $request->get('role'));
            }

            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            $users = $query->orderBy('created_at', 'desc')->get();

            // Create CSV content
            $csvContent = "Name,Email,Phone,Role,Status,Created At\n";
            foreach ($users as $user) {
                $csvContent .= implode(',', [
                    '"' . str_replace('"', '""', $user->name) . '"',
                    '"' . str_replace('"', '""', $user->email) . '"',
                    '"' . str_replace('"', '""', $user->phone ?? '') . '"',
                    '"' . str_replace('"', '""', $user->role) . '"',
                    '"' . str_replace('"', '""', $user->status) . '"',
                    '"' . $user->created_at->format('Y-m-d H:i:s') . '"',
                ]) . "\n";
            }

            $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export users data');
        }
    }

    /**
     * Get role permissions for preview
     */
    public function getRolePermissions(Request $request)
    {
        $role = $request->get('role');
        
        if (!$role) {
            return response()->json(['permissions' => []]);
        }

        // Define permissions for each role
        $rolePermissions = [
            'super_admin' => [
                ['name' => 'Full System Access', 'description' => 'Complete access to all system features'],
                ['name' => 'User Management', 'description' => 'Create, edit, delete all users'],
                ['name' => 'System Configuration', 'description' => 'Modify system settings'],
                ['name' => 'Backup & Restore', 'description' => 'Create and restore system backups'],
            ],
            'admin' => [
                ['name' => 'Content Management', 'description' => 'Manage news, announcements, and pages'],
                ['name' => 'User Management', 'description' => 'Manage operators and regular users'],
                ['name' => 'Population Data', 'description' => 'Manage village population data'],
                ['name' => 'Contact Messages', 'description' => 'View and respond to contact messages'],
            ],
            'operator' => [
                ['name' => 'Content Creation', 'description' => 'Create and edit content'],
                ['name' => 'Population Entry', 'description' => 'Enter and update population data'],
                ['name' => 'Message Management', 'description' => 'View and manage contact messages'],
            ],
            'user' => [
                ['name' => 'Profile Management', 'description' => 'Manage own profile and settings'],
                ['name' => 'Content Viewing', 'description' => 'Access to view published content'],
            ]
        ];

        return response()->json([
            'permissions' => $rolePermissions[$role] ?? []
        ]);
    }

    /**
     * Check if current user can assign specified role
     */
    private function canAssignRole($role)
    {
        switch ($role) {
            case 'super_admin':
                return Gate::allows('assign-super-admin-role');
            case 'admin':
                return Gate::allows('assign-admin-role');
            case 'operator':
                return Gate::allows('assign-operator-role');
            case 'user':
                return true; // Anyone can assign user role
            default:
                return false;
        }
    }
}