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
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;
use Shanerbaner82\PanelRoles\PanelRoles;

class PartesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('partes')
            ->path('partes')
            ->login()
            ->colors([
                'primary' => Color::Lime,
            ])
            // ->discoverResources(in: app_path('Filament/Partes/Resources'), for: 'App\\Filament\\Partes\\Resources')
            // ->discoverPages(in: app_path('Filament/Partes/Pages'), for: 'App\\Filament\\Partes\\Pages')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Partes/Widgets'), for: 'App\\Filament\\Partes\\Widgets')
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
                    ->restrictedRoles(['super_admin', 'Analista']),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
