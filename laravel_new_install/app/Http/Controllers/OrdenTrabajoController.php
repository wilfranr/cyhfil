<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class OrdenTrabajoController extends Controller
{
    public function generarPDF($id)
{
    $empresas = Empresa::all();
    $ordenTrabajo = OrdenTrabajo::with(['tercero', 'transportadora', 'direccion.city', 'direccion.state'])->where('id', $id)->first();
    $empresaActiva = Empresa::where('estado', true)->first();

    $pdf = PDF::loadView('pdf.ordenTrabajo', compact('ordenTrabajo', 'empresas', 'empresaActiva'))->setPaper('a5', 'landscape');  

    $filename = 'Guia-' . $ordenTrabajo->tercero->numero_documento . '-' . now()->format('Y-m-d') . '.pdf';

    return $pdf->download($filename);
}
}
