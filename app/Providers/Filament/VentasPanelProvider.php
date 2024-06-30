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
use Filament\Navigation\MenuItem;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Shanerbaner82\PanelRoles\PanelRoles;

class VentasPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandName('Venta de Repuestos')
            ->brandLogo(asset('images/logo.png'))
            ->id('ventas')
            ->path('ventas')
            ->login()
            ->colors([
                'primary' => Color::Slate,
            ])
            ->globalSearchKeyBindings(['ctrl+b'])
            ->sidebarCollapsibleOnDesktop()
            ->userMenuItems([
                MenuItem::make('Profile')
                    ->icon('heroicon-s-user')
                    ->label('Perfil')
                    ->url(''),
                MenuItem::make('Settings')
                    ->icon('heroicon-s-cog')
                    ->label('Configuración')
                    ->url(''),
                MenuItem::make('TRM')
                    ->icon('heroicon-s-currency-dollar')
                    ->label('TRM del Día')
                    ->url('\ventas\trm-settings'),
            ])

            // ->discoverResources(in: app_path('Filament/Ventas/Resources'), for: 'App\\Filament\\Ventas\\Resources')
            // ->discoverPages(in: app_path('Filament/Ventas/Pages'), for: 'App\\Filament\\Ventas\\Pages')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Ventas/Widgets'), for: 'App\\Filament\\Ventas\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                \App\Filament\Resources\PedidosResource\Widgets\StatsOverview::make(),

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
            ])
            ->plugins([
                SpotlightPlugin::make(),
                FilamentShieldPlugin::make(),
                PanelRoles::make()
                    ->roleToAssign('developer')
                    ->restrictedRoles(['super_admin', 'Vendedor']),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
