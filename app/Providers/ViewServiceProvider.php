<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\ProductosController;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Compartir categorías del navbar en todas las vistas que usen el componente navbar
        View::composer('components.navbar', function ($view) {
            $categoriasNavbar = \App\Models\CategoriaLanding::limit(5)->get()->map(function($categoria) {
                // Cargar solo las subcategorías marcadas para mostrar en navbar, ordenadas
                $categoria->setRelation('subcategorias', $categoria->subcategorias()
                    ->where('mostrar_en_navbar', true)
                    ->orderBy('orden_navbar', 'asc')
                    ->get());
                return $categoria;
            });
            
            $view->with('categoriasNavbar', $categoriasNavbar);
        });
    }
}
