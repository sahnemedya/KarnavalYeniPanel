<?php

use App\Http\Middleware\Authenticate;
use App\Models\Permission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LoginPageAuthControl;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // GEO Middleware'i web grubuna ekle (otomatik çalışır)
        $middleware->web(append: [
            \App\Http\Middleware\GeoMiddleware::class,
            \App\Http\Middleware\LanguageMiddleware::class,
        ]);

        // Middleware alias'ları
        $middleware->alias([
            // Giriş Gerektiren Rotaların Kontrolü İçin
            'auth' => Authenticate::class,
            // Login Page Erişiminde Kullanıcı Girişi Var mı Kontrolu
            'loginPageAuthControl' => LoginPageAuthControl::class,
            // Kullanıcı Yetki İzin Kontrolü
            'permission' => \App\Http\Middleware\CheckPermission::class,
            // GEO Middleware alias (isteğe bağlı kullanım için)
            'geo' => \App\Http\Middleware\GeoMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
