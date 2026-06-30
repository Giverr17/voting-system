<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoteMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Live status comes from the DB (toggled by admins); config('election.status')
        // is only the initial fallback used before the setting is seeded.
        if (!Setting::isElectionOpen()) {
            abort(403, 'Election is not opened');
        }

        return $next($request);
    }
}
