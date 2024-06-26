<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\PermissionServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'prevent-demo-actions' => \App\Http\Middleware\PreventDemoUserActions::class,
            'Alert' => RealRashid\SweetAlert\Facades\Alert::class,
        ]);
        $middleware->appendToGroup('web', \RealRashid\SweetAlert\ToSweetAlert::class,);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
