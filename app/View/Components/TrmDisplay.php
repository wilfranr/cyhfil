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
        // Obtener la TRM real desde una API
        $response = Http::get('https://www.datos.gov.co/resource/32sa-8pi3.json');
        $data = $response->json();
        return $data[0]['valor'];
    }

    public function render()
    {
        return view('components.trm-display', [
            'trm' => $this->trm
        ]);
    }
}