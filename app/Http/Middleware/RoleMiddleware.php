<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request and check user roles.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Admin has access to everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        $userRole = $user->role?->name;

        if (! in_array($userRole, $roles)) {
            return response()->json([
                'message' => 'Forbidden: You do not have the required role to perform this action.',
            ], 403);
        }

        return $next($request);
    }
}
