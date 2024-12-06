<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectUnauthorizedPanelAccess
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // Excluir rutas de login para evitar bucles de redirección
        if ($request->is('home/login') || $request->is('logout') || $request->is('*/logout')) {
            return $next($request);
        }
        

        // Si el usuario no está autenticado, redirige al login
        if (!$user) {
            return redirect('/home/login');
        }

        // Mapear roles a sus rutas correspondientes
        $panelRoutesByRole = [
            'super_admin' => '/admin',
            'Administrador' => '/admin',
            'Vendedor' => '/ventas',
            'Logistica' => '/logistica',
            'Analista' => '/partes',
        ];

        // Redirige según el rol
        foreach ($panelRoutesByRole as $role => $route) {
            if ($user->hasRole($role)) {
                if ($request->is(trim($route, '/'))) {
                    return $next($request); // Si ya está en la ruta correcta, permite el acceso
                }
                return redirect($route); // Redirige al panel correspondiente
            }
        }

        // Si no tiene un rol válido, redirige al login
        return redirect('/home/login');
    }
}
