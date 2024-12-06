<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Resources\TercerosResource\RelationManagers;
use App\Models\Empresa;
use Filament\Navigation\MenuItem;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Shanerbaner82\PanelRoles\PanelRoles;

class VentasPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $empresaActiva = Empresa::where('estado', true)->first();

        return $panel

            ->id('ventas')
            ->path('ventas')
            ->login()
            ->profile(isSimple: false)
            ->passwordReset()
            ->brandName($empresaActiva ? $empresaActiva->nombre : 'Venta de Repuestos')
            ->brandLogo($empresaActiva ? asset('storage/' . $empresaActiva->logo_dark) : asset('images/logo.png'))
            ->darkModeBrandLogo($empresaActiva ? asset('storage/' . $empresaActiva->logo_light) : asset('images/logo.png'))
            ->colors([
                'primary' => Color::Violet,
                'secondary' => Color::Pink,
            ])
            ->globalSearchKeyBindings(['ctrl+b'])
            ->sidebarCollapsibleOnDesktop()
            // ->maxContentWidth('full')
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->userMenuItems([
                // MenuItem::make('Settings')
                //     ->icon('heroicon-s-cog')
                //     ->label('Configuración')
                //     ->url(''),
                MenuItem::make('TRM')
                    ->icon('heroicon-s-currency-dollar')
                    ->label('TRM del Día')
                    ->url('\ventas\trm-settings'),
            ])

            // ->discoverResources(in: app_path('Filament/Ventas/Resources'), for: 'App\\Filament\\Ventas\\Resources')
            // ->discoverPages(in: app_path('Filament/Ventas/Pages'), for: 'App\\Filament\\Ventas\\Pages')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->sidebarWidth('15 rem')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Ventas/Widgets'), for: 'App\\Filament\\Ventas\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                \App\Filament\Resources\PedidosResource\Widgets\StatsOverview::make(),
                \App\Filament\Widgets\PedidosChart::make(),
                \App\Filament\Resources\TercerosResource\Widgets\ClientesChart::make(),
                \App\Filament\Widgets\UltimosPedidos::make(),

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\RedirectUnauthorizedPanelAccess::class, // Aquí añades tu middleware personalizado

            ])
            ->plugins([
                SpotlightPlugin::make(),
                FilamentShieldPlugin::make(),
                PanelRoles::make()
                    ->restrictedRoles(['super_admin', 'Administrador', 'Vendedor']),
            ])
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
