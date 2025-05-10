<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use App\Services\OpenAIService;
use App\Models\Articulo;

class IdentificadorRepuestos extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static string $view = 'filament.pages.identificador-repuestos';
    protected static ?string $title = 'Identificador de Repuestos';
    protected static ?string $navigationGroup = 'IA';

    public ?array $data = [];
    public ?string $respuestaIA = null;

    public \Illuminate\Support\Collection $coincidencias;

    public function mount(): void
    {
        $this->form->fill();
        $this->coincidencias = collect();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('imagen')
                ->image()
                ->label('Sube la imagen del repuesto')
                ->directory('repuestos-temp')
                ->preserveFilenames()
                ->required(),
        ])->statePath('data');
    }


public function submit()
{
    $archivo = collect($this->data['imagen'] ?? [])->first();

    if (!$archivo) {
        Notification::make()
            ->title('Error')
            ->body('No se pudo obtener la imagen.')
            ->danger()
            ->send();
        return;
    }


$nombreArchivo = strtolower(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME));
$palabras = preg_split('/[\s_\-]+/', $nombreArchivo); // separa por espacio, guion y guion bajo

    // ✅ Coincidencias con artículos vía referencias

$this->coincidencias = Articulo::where(function ($query) use ($palabras) {
    foreach ($palabras as $palabra) {
        $query->orWhere('definicion', 'like', '%' . $palabra . '%')
              ->orWhere('descripcionEspecifica', 'like', '%' . $palabra . '%');
    }
})->limit(5)->get();

    // ✅ Simulación IA basada en nombre de archivo
    $mapa = [
        '1u3452rc'     => 'Diente de cuchara reforzado Caterpillar 1U3452RC, usado en retroexcavadoras.',
        'valvula'      => 'Válvula hidráulica 4SP-35 para sistemas de control en maquinaria Komatsu.',
        'bomba'        => 'Bomba de inyección Bosch VP44, común en motores diésel de montacargas.',
        'sensor'       => 'Sensor de presión Komatsu KP-34P, usado en excavadoras PC210.',
        'manguera'     => 'Manguera hidráulica reforzada Parker R17 para excavadora Volvo EC210.',
        'alternador'   => 'Alternador Delco Remy 24V para maquinaria John Deere.',
        'cilindro'     => 'Cilindro hidráulico frontal para cargador CAT 950G.',
        'filtro'       => 'Filtro de aire Donaldson P181126, para motores Caterpillar C7.',
        'inyector'     => 'Inyector electrónico Cummins ISX, de alta presión para camiones pesados.',
        'turbo'        => 'Turbo Holset HX35W usado en motores diésel de maquinaria Volvo y Cummins.',
    ];

    $respuesta = 'No se pudo identificar el repuesto.';

    foreach ($mapa as $clave => $descripcion) {
        if (str_contains($nombreArchivo, $clave)) {
            $respuesta = $descripcion;
            break;
        }
    }

    $this->respuestaIA = $respuesta;

    Notification::make()
        ->title('Resultado simulado IA')
        ->body($respuesta)
        ->success()
        ->send();
}
}
