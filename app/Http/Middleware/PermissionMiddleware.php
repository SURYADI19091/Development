<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->isActive()) {
            Auth::logout();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ], 403);
            }
            
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // If no specific permission required, just check admin panel access
        if (!$permission) {
            if (!Gate::allows('access-admin-panel')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke panel admin.'
                    ], 403);
                }
                
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke panel admin.');
            }
            
            return $next($request);
        }

        // Check specific permission
        if (!Gate::allows($permission)) {
            \Log::warning('Permission denied', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'permission' => $permission,
                'route' => $request->route()?->getName(),
                'url' => $request->url(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda tidak memiliki permission '{$permission}' untuk mengakses fitur ini."
                ], 403);
            }

            // For web requests, redirect with error message
            return redirect()->back()
                ->with('error', "Anda tidak memiliki permission '{$permission}' untuk mengakses fitur ini.")
                ->withInput();
        }

        return $next($request);
    }
}
