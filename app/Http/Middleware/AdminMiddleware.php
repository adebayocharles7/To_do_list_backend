<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Check if the user is authenticated and is an admin
        if ($request->user() && request()->user()->is_admin) {
            return $next($request);
        }

        // If not admin, return a 403 unauthorized response
        return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN); // HTTP 403
    }
}
