<?php

use App\Http\Controllers\Cotizacion;
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
    return redirect('/home');

    $user = Auth::user();
    $rol = $user->roles->first()->name;
    if ($user == null) {
        return redirect('/home');
    }
    elseif ($rol == 'Vendedor') {
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

});


Route::get('/pdf/generate/{id}', [Cotizacion::class, 'generate'])->name('pdf.cotizacion');