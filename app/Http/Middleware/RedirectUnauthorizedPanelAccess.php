<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectUnauthorizedPanelAccess
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // Excluir rutas de login y logout para evitar bucles
        if ($request->is('home/login') || $request->is('logout')) {
            return $next($request);
        }

        // Si el usuario no está autenticado, redirige al login
        if (!$user) {
            return redirect('/home/login');
        }

        // Mapear roles a las rutas base de sus paneles correspondientes
        $panelRoutesByRole = [
            'super_admin' => '/admin',
            'Administrador' => '/admin',
            'Vendedor' => '/ventas',
            'Logistica' => '/logistica',
            'Analista' => '/partes',
        ];

        // Obtener la URL base de la ruta actual
        $currentBasePath = '/' . explode('/', trim($request->path(), '/'))[0];

        // Verificar si la URL base pertenece al panel del usuario
        foreach ($panelRoutesByRole as $role => $basePath) {
            if ($user->hasRole($role)) {
                if ($currentBasePath === $basePath) {
                    return $next($request); // Permitir acceso a la ruta
                }
                return redirect($basePath); // Redirigir al panel base
            }
        }

        // Si no tiene un rol válido, redirige al login
        return redirect('/home/login');
    }
}
