<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Src\Core\Http\Middleware\EnsureTeamPermission;
use Src\Core\Http\Middleware\ResolveTenantMiddleware;
use Src\Core\Shared\Exceptions\TenantNotResolvedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ── Daftarkan alias middleware ─────────────────────────────────
        // Alias ini digunakan di route definition dan Filament panel.
        $middleware->alias([
            'resolve.tenant' => ResolveTenantMiddleware::class,
            'team.permission' => EnsureTeamPermission::class,

            // Spatie Permission middleware (sudah include di package):
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        // ── Web middleware group ───────────────────────────────────────
        // ResolveTenantMiddleware di-append ke grup web agar berlaku
        // untuk semua request Filament panel yang authenticated.
        $middleware->web(append: [
            ResolveTenantMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle TenantNotResolvedException dengan response yang informatif
        $exceptions->render(function (
            TenantNotResolvedException $e,
            $request
        ) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
            abort(500, $e->getMessage());
        });
    })->create();
