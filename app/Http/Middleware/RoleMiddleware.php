<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login-index');
        }
        // dd(Auth::user()->role, $role);
        
        
        if (Auth::user()->role->value !== $role) {
            abort(403,'You do not have permission to access this page.');
            // return redirect()->route('logout-index');
        }
        return $next($request);
    }
}
