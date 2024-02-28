<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty(auth()->user()->getPermissionsViaRoles()->toArray()) || !empty(auth()->user()->getAllPermissions()->toArray()) || Auth::user()->rolles == 'admin') {
            return $next($request);
        } else {
            return redirect()->route('user.dashboard.index');
        }
    }
}
