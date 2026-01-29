<?php

use App\Http\Middleware\CustomRoute;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\VoteMiddleWare;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'=>RoleMiddleware::class,
            'election.open'=>VoteMiddleWare::class,
            'no-cache'=>NoCache::class,
            // 'route'=>CustomRoute::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
