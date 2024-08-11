<?php

use App\Http\Controllers\Cotizacion;
use App\Http\Controllers\OrdenCompraController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


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


Route::get('/pdf/generate/{id}', [Cotizacion::class, 'generate'])->name('pdf.cotizacion');

//ruta orden de compra
Route::get('/pdf/generateOrdenCompra/{id}', [OrdenCompraController::class, 'generate'])->name('pdf.ordenCompra');