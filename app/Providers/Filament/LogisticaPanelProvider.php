<?php

namespace App\Providers\Filament;

use App\Models\Empresa;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Shanerbaner82\PanelRoles\PanelRoles;

class LogisticaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $empresaActiva = Empresa::where('estado', true)->first();
        return $panel
            ->id('logistica')
            ->path('logistica')
            ->brandLogo($empresaActiva ? asset('storage/' . $empresaActiva->logo_dark) : asset('images/logo.png'))
            ->darkModeBrandLogo($empresaActiva ? asset('storage/' . $empresaActiva->logo_light) : asset('images/logo.png'))
            ->login()
            ->profile(isSimple: false)
            ->colors([
                'primary' => Color::Teal,
                'secondary' => Color::Indigo,
            ])
            ->globalSearchKeyBindings(['ctrl+b'])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->sidebarWidth('15 rem')
            // ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            // ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverResources(in: app_path('Filament/Logistica/Resources'), for: 'App\\Filament\\Logistica\\Resources')
            ->discoverPages(in: app_path('Filament/Logistica/Pages'), for: 'App\\Filament\\Logistica\\Pages')
            // ->pages([
            //     Pages\Dashboard::class,
            // ])

            ->discoverWidgets(in: app_path('Filament/Logistica/Widgets'), for: 'App\\Filament\\Logistica\\Widgets')
            ->widgets([
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
                \App\Http\Middleware\RedirectUnauthorizedPanelAccess::class, // Aquí añades tu middleware personalizado

            ])
            ->plugins([
                SpotlightPlugin::make(),
                FilamentShieldPlugin::make(),
                PanelRoles::make()
                    ->restrictedRoles(['super_admin', 'Administrador', 'Logistica']),
            ])
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}