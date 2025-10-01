<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class GateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $gate, ...$parameters): Response
    {
        if (Gate::denies($gate, $parameters)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini.',
                    'gate' => $gate
                ], 403);
            }

            abort(403, 'Anda tidak memiliki akses untuk melakukan tindakan ini.');
        }

        return $next($request);
    }
}