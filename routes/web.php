<?php

use App\Http\Controllers\Cotizacion;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\OrdenTrabajoController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/home/login');  // Redirige al login de Filament
    }
    
    $rol = Auth::user()->roles->first()->name;
    switch ($rol) {
        case 'Vendedor':
            return redirect('/ventas');
        case 'Administrador':
        case 'super-admin':
            return redirect('/admin');
        case 'Analista':
            return redirect('/partes');
        case 'Logistica':
            return redirect('/logistica');
        default:
            return redirect('/home');
    }
});





Route::get('/pdf/generate/{id}', [Cotizacion::class, 'generate'])->name('pdf.cotizacion');

//ruta orden de compra
Route::get('/pdf/generateOrdenCompra/{id}', [OrdenCompraController::class, 'generate'])->name('pdf.ordenCompra');

Route::get('/ordenTrabajo/{id}/pdf', [OrdenTrabajoController::class, 'generarPDF'])->name('ordenTrabajo.pdf');

Route::middleware(['auth'])->group(function () {
    // Route::get('/chat/messages', [ChatController::class, 'fetchMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    Route::get('/auth-status', function () {
        return response()->json(['isAuthenticated' => Auth::check()]);
    })->name('auth.status');
    
});

Route::post('/broadcasting/auth', function () {
    return response()->json([], 200);
});


