<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationOpen
{
    /**
     * Block access to registration when an admin has closed it.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Setting::isRegistrationOpen()) {
            abort(403, 'Registration is currently closed');
        }

        return $next($request);
    }
}
