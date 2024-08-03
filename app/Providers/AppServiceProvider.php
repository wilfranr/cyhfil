<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;

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
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            // $panelSwitch
            // ->modalWidth('xl');
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
    }
}
