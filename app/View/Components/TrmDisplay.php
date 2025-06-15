<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Http;

class TrmDisplay extends Component
{
    public $trm;

    public function __construct()
    {
        $this->trm = $this->getTrm();
    }

    protected function getTrm()
    {
        try {
            $response = Http::timeout(5)->get('https://www.datos.gov.co/resource/32sa-8pi3.json');
            dd($response->status());

            if ($response->ok()) {
                $data = $response->json();

                if (!empty($data[0]['valor']) && is_numeric($data[0]['valor'])) {
                    return (float) $data[0]['valor'];
                }
            }
        } catch (\Exception $e) {
            // Opcional: puedes loguear el error
            \Log::error('Error al obtener la TRM: ' . $e->getMessage());
        }

        return 0; // Valor por defecto si falla la consulta
    }

    public function render()
    {
        return view('components.trm-display', [
            'trm' => $this->trm,
        ]);
    }
}
