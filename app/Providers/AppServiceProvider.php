<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Facades\Filament;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js; // Asegúrate de importar esta clase
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar PanelSwitch
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch
                ->modalWidth('sm')
                ->slideOver()
                ->icons([
                    'admin' => 'heroicon-o-square-2-stack',
                    'app' => 'heroicon-o-star',
                ])
                ->iconSize(16)
                ->labels([
                    'admin' => 'Admin Panel',
                    'app' => 'SaaS Application'
                ]);
        });

        

        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            // Obtener el rol del usuario
            $rol = Auth::user()->roles->first()->name;
            
            // Redirigir al usuario según su rol
            if ($rol == 'Vendedor') {
                redirect()->to('/ventas');
            } elseif ($rol == 'Administrador') {
                redirect()->to('/admin');
            } elseif ($rol == 'Analista') {
                redirect()->to('/partes');
            } elseif ($rol == 'Logistica') {
                redirect()->to('/logistica');
            } elseif ($rol == 'super-admin' || $rol == 'Administrador') {
                redirect('/admin');
            }
        }
        // Registrar el script JavaScript personalizado con FilamentAsset
        FilamentAsset::register([
            Js::make('custom-chat', __DIR__ . '/../../resources/js/custom-chat.js'), // Usamos Js::make() para registrar
        ]);

        // Puedes registrar archivos CSS de manera similar con FilamentAsset::register()
        FilamentAsset::register([
            // Css::make('custom-chat', __DIR__ . '/../../resources/css/custom-chat.css'), // Usamos Css::make() para registrar
            Css::make('custom-chat', __DIR__ . '/../../resources/css/custom-layout.css'), // Usamos Css::make() para registrar
        ]);

        FilamentAsset::registerScriptData([
            'isAuthenticated' => auth()->check(),
        ]);
    }
}
