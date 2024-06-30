<?php

use App\Http\Controllers\Cotizacion;
use Barryvdh\DomPDF\Facade\Pdf;
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
    $user = auth()->user();
    if ($user == null) {
        return redirect('/home');
    }
    $rol = $user->roles->first()->name;
    if ($rol == 'Vendedor') {
        return redirect('/ventas');
    } elseif ($rol == 'Administrador') {
        return redirect('/admin');
    } elseif ($rol == 'Analista') {
        return redirect('/partes');
    } elseif ($rol == 'logistica') {
        return redirect('/logistica');
    } else {
        return redirect('/home');
    }

    // return redirect('/home');
});

//ruta dashboard
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

//panel ventas
// Route::get('/ventas', function () {
//     return view('panel.ventas');
// })->middleware(['auth'])->name('panel.ventas');


Route::get('/pdf/generate/{id}', [Cotizacion::class, 'generate'])->name('pdf.cotizacion');