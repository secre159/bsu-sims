<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartmentAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $departmentId = $request->route('department');

        // If department parameter is provided in route, verify access
        if ($departmentId && $user->department_id != $departmentId) {
            abort(403, 'You do not have access to this department.');
        }

        return $next($request);
    }
}
