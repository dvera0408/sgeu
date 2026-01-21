<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // Controlador de perfil de Breeze
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EdicionController;
use App\Http\Controllers\ModalidadController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

// Si intentan entrar a la raíz '/', los mandamos al login
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // --- Redirección del Dashboard por defecto de Breeze ---
    // Lo redirigimos a TU dashboard de admin
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // --- Rutas de Perfil (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- TU SISTEMA DE GESTIÓN (Tus rutas antiguas restauradas) ---
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // Asegúrate de que esta vista exista
        })->name('dashboard');

        // Categorías
        Route::resource('categorias', CategoriaController::class);
        Route::patch('categorias/{categoria}/toggle', [CategoriaController::class, 'toggleStatus'])
             ->name('categorias.toggle');

        // Eventos
        Route::resource('eventos', EventoController::class);
        Route::patch('eventos/{evento}/toggle', [EventoController::class, 'toggleStatus'])
             ->name('eventos.toggle');

        // Ediciones
        Route::resource('ediciones', EdicionController::class)->parameters([
            'ediciones' => 'edicion'
        ]);
        Route::patch('ediciones/{edicion}/estado', [EdicionController::class, 'cambiarEstado'])
             ->name('ediciones.cambiar-estado');

        // Modalidades
        Route::resource('modalidades', ModalidadController::class)->parameters([
            'modalidades' => 'modalidad'
        ]);
        Route::patch('modalidades/{modalidad}/toggle', [ModalidadController::class, 'toggleStatus'])
             ->name('modalidades.toggle');
    });
});

// Incluir las rutas de autenticación de Breeze (Login, Logout, etc.)
require __DIR__.'/auth.php';
