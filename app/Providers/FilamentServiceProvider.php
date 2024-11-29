<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    protected function getTrm()
{
    return Cache::remember('daily_trm', now()->endOfDay(), function () {
        try {
            $response = Http::get('https://www.datos.gov.co/resource/32sa-8pi3.json');

            if ($response->successful()) {
                $data = $response->json();
                return $data[0]['valor'] ?? 'N/A';
            }
        } catch (\Exception $e) {
            \Log::error('Error obteniendo la TRM: ' . $e->getMessage());
        }

        return 'N/A'; // Valor por defecto
    });
}
    public function boot()
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): View => view('components.trm-display', ['trm' => $this->getTrm()])
        );
        
        
    }
}
