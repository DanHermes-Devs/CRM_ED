<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CobranzaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\API\ventas\VentasController;
use App\Http\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dasboard');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/crear-usuario', [UsuarioController::class, 'create'])->name('crear-usuario');
    Route::post('/crear-usuario', [UsuarioController::class, 'store'])->name('store-usuario');
    Route::get('/editar-usuario/{id}', [UsuarioController::class, 'edit'])->name('editar-usuario');
    Route::put('/editar-usuario/{id}', [UsuarioController::class, 'update'])->name('actualizar-usuario');
    Route::delete('/eliminar-usuario/{id}', [UsuarioController::class, 'destroy'])->name('eliminar-usuario');
    Route::get('/import-users', [UsuarioController::class, 'formImport'])->name('formImport');
    Route::post('/users-import', [UsuarioController::class, 'importUsers'])->name('importUsers');
    
    Route::resource('/roles', RoleController::class);
    Route::resource('/paises', PaisController::class);
    Route::resource('/proyectos', ProjectController::class);
    Route::resource('/grupos', GroupController::class);

    Route::get('/cobranza', [CobranzaController::class, 'index'])->name('cobranza.index');
    Route::post('/cobranza/asignar/{recibo_id}', [CobranzaController::class, 'asignarRecibo'])->name('cobranza.asignar');

    Route::get('/cobranza/cancelar/{recibo_id}', [CobranzaController::class, 'showCancelarRecibo'])->name('cobranza.cancelar.show');
    Route::post('/cobranza/cancelar/{recibo_id}', [CobranzaController::class, 'cancelarRecibo'])->name('cobranza.cancelar');
    
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
});

Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index');
Route::get('/usuario-venta/{id}', [VentasController::class, 'show'])->name('ver-usuario');
Route::get('/ventas-export', [VentasController::class, 'exportVentas'])->name('ventas.exportVentas');
Route::get('/ventas-import-form', [VentasController::class, 'formImportVentas'])->name('ventas.formImportVentas');
Route::post('/ventas-import', [VentasController::class, 'importVentas'])->name('ventas.importVentas');

// Formulario de prueba para insercion de datos
Route::get('/form', [VentasController::class, 'form'])->name('ventas.form');