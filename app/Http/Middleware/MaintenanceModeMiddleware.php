<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if application is in maintenance mode
        if (app()->isDownForMaintenance()) {
            // Allow access for admin users during maintenance
            if (Auth::check() && Gate::allows('access-maintenance-mode')) {
                return $next($request);
            }

            // Show maintenance page for regular users
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sistem sedang dalam pemeliharaan. Silakan coba lagi nanti.',
                    'maintenance' => true
                ], 503);
            }

            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}