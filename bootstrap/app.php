<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleMaxExecutionTime;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckLoginStatus;
use App\Http\Middleware\MustAdmin;
use App\Http\Middleware\MustOwnerManager;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'HandleMaxExecutionTime' => HandleMaxExecutionTime::class,
            'auth' => Authenticate::class,
            'MustAdmin' => MustAdmin::class,
            'MustOwnerManager' => MustOwnerManager::class,
            'checkStatus' => CheckLoginStatus::class,
        ]);
        // $middleware->append('group-auth', [
        //     Authenticate::class,
        //     HandleMaxExecutionTime::class,
        // ]);
        // $middleware->append([
        //     'auth' => Authenticate::class
        // ]);
        $middleware->use([
            \App\Http\Middleware\ThrottleLoginAttempts::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    // $app->register(\Barryvdh\DomPDF\ServiceProvider::class);
    // $app->configure('dompdf');