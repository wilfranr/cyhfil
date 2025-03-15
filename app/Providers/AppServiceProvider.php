<?php

namespace App\Providers;

use App\Models\Pedido;
use App\Observers\PedidoObserver;
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
                ])
                ->visible(fn (): bool => auth()->user()?->hasRole('Administrador') || auth()->user()?->hasRole('super_admin'));
        });

        // Registrar el script JavaScript personalizado con FilamentAsset
        FilamentAsset::register([
            Js::make('custom-chat', __DIR__ . '/../../resources/js/custom-chat.js'), // Usamos Js::make() para registrar
            Js::make('pusher', 'https://js.pusher.com/7.0/pusher.min.js'),
            Js::make('echo', 'https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js'),
        ]);

        // Puedes registrar archivos CSS de manera similar con FilamentAsset::register()
        FilamentAsset::register([
            // Css::make('custom-chat', __DIR__ . '/../../resources/css/custom-chat.css'), // Usamos Css::make() para registrar
            Css::make('custom-chat', __DIR__ . '/../../resources/css/custom-layout.css'), // Usamos Css::make() para registrar
        ]);

        FilamentAsset::registerScriptData([
            'isAuthenticated' => auth()->check(),
        ]);

        Pedido::observe(PedidoObserver::class);
    }
}