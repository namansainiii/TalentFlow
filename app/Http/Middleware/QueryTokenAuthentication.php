<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QueryTokenAuthentication
{
    /**
     * Set Authorization header from query token if present.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->filled('token') && ! $request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer '.$request->query('token'));
        }

        return $next($request);
    }
}
