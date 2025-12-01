<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     * Only allows users with 'admin' role (or null role for legacy admin).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized. Please log in.');
        }

        $role = auth()->user()->role;
        
        // Allow admin role or null (legacy admin users)
        if ($role !== 'admin' && $role !== null) {
            abort(403, 'Unauthorized. Only administrators can access this resource.');
        }

        return $next($request);
    }
}
