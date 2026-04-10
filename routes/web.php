<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // USUARIOS
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->middleware('can:usuarios.ver')->name('index');
        Route::get('/data', [UsuarioController::class, 'data'])->middleware('can:usuarios.ver')->name('data');
        Route::get('/create', [UsuarioController::class, 'create'])->middleware('can:usuarios.crear')->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->middleware('can:usuarios.crear')->name('store');
        Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->middleware('can:usuarios.editar')->name('edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->middleware('can:usuarios.editar')->name('update');
        Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->middleware('can:usuarios.eliminar')->name('destroy');
    });

    // ROLES
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('can:roles.ver')->name('index');
        Route::get('/data', [RoleController::class, 'data'])->middleware('can:roles.ver')->name('data');
        Route::get('/create', [RoleController::class, 'create'])->middleware('can:roles.crear')->name('create');
        Route::post('/', [RoleController::class, 'store'])->middleware('can:roles.crear')->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('can:roles.editar')->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->middleware('can:roles.editar')->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('can:roles.eliminar')->name('destroy');
    });

    // PERMISOS
    Route::prefix('permisos')->name('permisos.')->group(function () {
        Route::get('/', [PermisoController::class, 'index'])->middleware('can:permisos.ver')->name('index');
        Route::get('/data', [PermisoController::class, 'data'])->middleware('can:permisos.ver')->name('data');
        Route::get('/create', [PermisoController::class, 'create'])->middleware('can:permisos.crear')->name('create');
        Route::post('/', [PermisoController::class, 'store'])->middleware('can:permisos.crear')->name('store');
        Route::get('/{permiso}/edit', [PermisoController::class, 'edit'])->middleware('can:permisos.editar')->name('edit');
        Route::put('/{permiso}', [PermisoController::class, 'update'])->middleware('can:permisos.editar')->name('update');
        Route::delete('/{permiso}', [PermisoController::class, 'destroy'])->middleware('can:permisos.eliminar')->name('destroy');
    });

    // CATEGORIAS
    Route::prefix('categorias')->name('categorias.')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])->middleware('can:categorias.ver')->name('index');
        Route::get('/data', [CategoriaController::class, 'data'])->middleware('can:categorias.ver')->name('data');
        Route::get('/create', [CategoriaController::class, 'create'])->middleware('can:categorias.crear')->name('create');
        Route::post('/', [CategoriaController::class, 'store'])->middleware('can:categorias.crear')->name('store');
        Route::get('/{categoria}/edit', [CategoriaController::class, 'edit'])->middleware('can:categorias.editar')->name('edit');
        Route::put('/{categoria}', [CategoriaController::class, 'update'])->middleware('can:categorias.editar')->name('update');
        Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->middleware('can:categorias.eliminar')->name('destroy');
    });

    // ITEMS
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->middleware('can:items.ver')->name('index');
        Route::get('/data', [ItemController::class, 'data'])->middleware('can:items.ver')->name('data');
        Route::get('/create', [ItemController::class, 'create'])->middleware('can:items.crear')->name('create');
        Route::post('/', [ItemController::class, 'store'])->middleware('can:items.crear')->name('store');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->middleware('can:items.editar')->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->middleware('can:items.editar')->name('update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->middleware('can:items.eliminar')->name('destroy');
    });

    // VENTAS
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->middleware('can:ventas.ver')->name('index');
        Route::get('/data', [SaleController::class, 'data'])->middleware('can:ventas.ver')->name('data');
        Route::get('/create', [SaleController::class, 'create'])->middleware('can:ventas.crear')->name('create');
        Route::post('/', [SaleController::class, 'store'])->middleware('can:ventas.crear')->name('store');
        Route::get('/{venta}/edit', [SaleController::class, 'edit'])->middleware('can:ventas.editar')->name('edit');
        Route::put('/{venta}', [SaleController::class, 'update'])->middleware('can:ventas.editar')->name('update');
        Route::get('/{venta}/factura/pdf', [SaleController::class, 'facturaPdf'])->middleware('can:ventas.ver')->name('factura.pdf');
        Route::delete('/{venta}', [SaleController::class, 'destroy'])->middleware('can:ventas.eliminar')->name('destroy');
    });

    // REPORTES
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->middleware('can:reportes.ver')->name('index');
        Route::get('/data', [ReportController::class, 'data'])->middleware('can:reportes.ver')->name('data');
        Route::get('/ventas/items/pdf', [ReportController::class, 'itemSalesPdf'])->middleware('can:reportes.ver')->name('ventas.items.pdf');
    });
});


require __DIR__ . '/auth.php';

use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');
