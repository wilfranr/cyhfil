<?php

use App\Http\Controllers\Cotizacion;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\OrdenTrabajoController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    if (Auth::user() == null) {
        return redirect('/home');
    } else {
        $rol = Auth::user()->roles->first()->name;
        if ($rol == 'Vendedor') {
            return redirect('/ventas');
        } elseif ($rol == 'Administrador') {
            return redirect('/admin');
        } elseif ($rol == 'Analista') {
            return redirect('/partes');
        } elseif ($rol == 'Logistica') {
            return redirect('/logistica');
        } elseif ($rol == 'super-admin' || $rol == 'Administrador') {
            return redirect('/admin');
        }
    }
});
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
Route::middleware('auth')->get('/chat/messages', [ChatController::class, 'fetchMessages']);

Route::get('/pdf/generate/{id}', [Cotizacion::class, 'generate'])->name('pdf.cotizacion');

//ruta orden de compra
Route::get('/pdf/generateOrdenCompra/{id}', [OrdenCompraController::class, 'generate'])->name('pdf.ordenCompra');

Route::get('/ordenTrabajo/{id}/pdf', [OrdenTrabajoController::class, 'generarPDF'])->name('ordenTrabajo.pdf');

Route::post('/broadcasting/auth', function () {
    return response()->json([], 200);
});

//Ruta para descarga Plantilla de excel en listas
Route::get('/downloadExcel', function () {
    $filePath = public_path('storage/PlantillaListas.xlsx');
    $fileName = 'PlantillaListas.xlsx';

    return response()->download($filePath, $fileName);
})->name('downloadExcel');