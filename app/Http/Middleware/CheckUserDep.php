<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserDep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (auth()->user()->hasDirectPermission('companies')) {
                return redirect()->route('company.dashboard.index');
            }
            if (auth()->user()->hasDirectPermission('employees')) {
                return $next($request);
            }
            if (Auth::user()->rolles == 'admin') {
                return $next($request);
            }
            return redirect()->route('inactive');
        }
        return redirect()->route('login');
    }
}
