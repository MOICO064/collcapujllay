<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::prefix('usuarios')->name('usuarios.')->group(function () {

        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/data', [UsuarioController::class, 'data'])->name('data');
        Route::get('/create', [UsuarioController::class, 'create'])->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::get('/{usuario}/edit', [UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
        Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/data', [RoleController::class, 'data'])->name('data');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('permisos')->name('permisos.')->group(function () {

        Route::get('/', [PermisoController::class, 'index'])->name('index');
        Route::get('/data', [PermisoController::class, 'data'])->name('data');
        Route::get('/create', [PermisoController::class, 'create'])->name('create');
        Route::post('/', [PermisoController::class, 'store'])->name('store');
        Route::get('/{permiso}/edit', [PermisoController::class, 'edit'])->name('edit');
        Route::put('/{permiso}', [PermisoController::class, 'update'])->name('update');
        Route::delete('/{permiso}', [PermisoController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('categorias')->name('categorias.')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])->name('index');
        Route::get('/data', [CategoriaController::class, 'data'])->name('data');
        Route::get('/create', [CategoriaController::class, 'create'])->name('create');
        Route::post('/', [CategoriaController::class, 'store'])->name('store');
        Route::get('/{categoria}/edit', [CategoriaController::class, 'edit'])->name('edit');
        Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('update');
        Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/data', [ItemController::class, 'data'])->name('data');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('promociones')->name('promociones.')->group(function () {
        Route::get('/', [PromotionController::class, 'index'])->name('index');
        Route::get('/data', [PromotionController::class, 'data'])->name('data');
        Route::get('/create', [PromotionController::class, 'create'])->name('create');
        Route::post('/', [PromotionController::class, 'store'])->name('store');
        Route::get('/{promotion}/edit', [PromotionController::class, 'edit'])->name('edit');
        Route::put('/{promotion}', [PromotionController::class, 'update'])->name('update');
        Route::delete('/{promotion}', [PromotionController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/data', [SaleController::class, 'data'])->name('data');
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/', [SaleController::class, 'store'])->name('store');
        Route::get('/{venta}/edit', [SaleController::class, 'edit'])->name('edit');
        Route::put('/{venta}', [SaleController::class, 'update'])->name('update');
        Route::get('/{venta}/factura/pdf', [SaleController::class, 'facturaPdf'])->name('factura.pdf');
        Route::delete('/{venta}', [SaleController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/ventas/items/pdf', [ReportController::class, 'itemSalesPdf'])->name('ventas.items.pdf');
    });
});


require __DIR__ . '/auth.php';

use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');
