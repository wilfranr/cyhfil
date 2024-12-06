<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\{Authenticate, DispatchServingFilamentEvent, DisableBladeIconComponents};
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Shanerbaner82\PanelRoles\PanelRoles;
use App\Filament\Widgets\OrdersPerMonthChart;
use App\Models\Empresa;

class DashboardPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {

        $empresaActiva = Empresa::where('estado', true)->first();

        return $panel
            ->brandName($empresaActiva ? $empresaActiva->nombre : 'Venta de Repuestos')
            ->brandLogo($empresaActiva ? asset('storage/' . $empresaActiva->logo_dark) : asset('images/logo.png'))
            ->darkModeBrandLogo($empresaActiva ? asset('storage/' . $empresaActiva->logo_light) : asset('images/logo.png'))
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
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
                    ->url('\admin\trm-settings'),
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                // TrmSettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Resources\PedidosResource\Widgets\StatsOverview::make(),
                \App\Filament\Resources\TercerosResource\Widgets\ClientesChart::make(),
                // \App\Filament\Widgets\PedidosChart::make(),
                // \App\Filament\Widgets\TrmInputWidget::class,
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
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 1,
                        'lg' => 1
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                PanelRoles::make()
                    ->restrictedRoles(['super_admin', 'Administrador']),

            ])
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,

            ]);
    }
}
