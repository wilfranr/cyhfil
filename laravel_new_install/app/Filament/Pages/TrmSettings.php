<?php

namespace App\Filament\Pages;

use App\Models\Empresa;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
        DB::table('empresas')
            ->where('estado', true)
            ->update(['trm' => $this->trm]);

        //Resetear el formulario después de guardar
        $this->form->fill([
            'trm' => $this->getTrm()
        ]);
        Notification::make('TRM actualizada correctamente');
       

    }

    protected function getTrm()
    {

        $latestTrm = DB::table('empresas')
            ->where('estado', true)
            ->value('trm');

        return $latestTrm ?? 0;
    }
}