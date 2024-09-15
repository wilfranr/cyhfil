<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\DB;

class TrmSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    // protected static ?string $slug = 'trm-settings';
    protected static string $view = 'filament.pages.trm-settings';

    // Añadir esta propiedad para ocultar de la barra lateral
    protected static bool $shouldRegisterNavigation = false;

    public $trm;

    public function mount(): void
    {
        $this->form->fill([
            'trm' => $this->getTrm(),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('trm')
                ->label('TRM del Día')
                ->required()
                ->numeric(),
        ];
    }

    public function submit()
    {
        // Guardar TRM en la base de datos
        DB::table('t_r_m_s')->updateOrInsert(
            ['date' => now()->toDateString()],
            ['trm' => $this->trm]
        );

        // Resetear el formulario después de guardar
        $this->form->fill([
            'trm' => $this->getTrm(),
        ]);        
    }

    protected function getTrm()
    {
        return DB::table('t_r_m_s')->value('trm') ?? 0;
    }
}

