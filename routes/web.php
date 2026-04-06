<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('usuarios')->name('usuarios.')->group(function () {

        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/data', [UsuarioController::class, 'data'])->name('data');
        Route::get('/create', [UsuarioController::class, 'create'])->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::get('/{usuario}', [UsuarioController::class, 'show'])->name('show');
        Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
        Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy');
    });
});


require __DIR__ . '/auth.php';

use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');
