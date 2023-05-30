<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CobranzaController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonalFileController;
use App\Http\Controllers\API\ventas\VentasController;
use App\Http\Controllers\API\Educacion\EducationController;

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

// Ruta para ejecutar un iSeed
Route::get('/seeders-backup', function () {
    Artisan::call('iseed users --force');
    Artisan::call('iseed ventas --force');
    Artisan::call('iseed attendances --force');
    Artisan::call('iseed receipts --force');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dasboard');
    Route::get('/filterODM', [HomeController::class, 'filter'])->name('filter-dashboard');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::get('/crear-usuario', [UsuarioController::class, 'create'])->name('crear-usuario');
    Route::post('/crear-usuario', [UsuarioController::class, 'store'])->name('store-usuario');
    Route::get('/editar-usuario/{id}', [UsuarioController::class, 'edit'])->name('editar-usuario');
    Route::put('/editar-usuario/{id}', [UsuarioController::class, 'update'])->name('actualizar-usuario');
    Route::delete('/eliminar-usuario/{id}', [UsuarioController::class, 'destroy'])->name('eliminar-usuario');
    Route::get('/import-users', [UsuarioController::class, 'formImport'])->name('formImport');
    Route::post('/users-import', [UsuarioController::class, 'importUsers'])->name('importUsers');

    // Rutas para el modulo de expedientes
    Route::get('/cargar-expediente-usuario/{id}', [UsuarioController::class, 'createExpediente'])->name('create.expedient');
    Route::post('/crear-expediente-usuario', [UsuarioController::class, 'crearExpediente'])->name('store.crearExpediente');

    // Rutas para las asistencias
    Route::get('/asistencias', [AttendanceController::class, 'index'])->name('asistencias');
    Route::get('/get-user-incidencia/{id}', [IncidentController::class, 'index'])->name('consultar-usuario');

    Route::resource('/roles', RoleController::class);
    Route::resource('/paises', PaisController::class);
    Route::resource('/proyectos', ProjectController::class);
    Route::resource('/grupos', GroupController::class);
    Route::resource('/educacion-uin', EducationController::class);

    Route::get('/cobranza', [CobranzaController::class, 'index'])->name('cobranza.index');
    Route::post('/cobranza/asignar/{recibo_id}', [CobranzaController::class, 'asignarRecibo'])->name('cobranza.asignar');
    Route::get('/cobranza/ver/{id}', [CobranzaController::class, 'edit'])->name('cobranza.edit');
    Route::put('/cobranza/actualizar/{id}', [CobranzaController::class, 'update'])->name('cobranza.update');

    Route::get('/cobranza/cancelar/{recibo_id}', [CobranzaController::class, 'showCancelarRecibo'])->name('cobranza.cancelar.show');
    Route::post('/cobranza/cancelar/{recibo_id}', [CobranzaController::class, 'cancelarRecibo'])->name('cobranza.cancelar');

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/cronjobs', [CronJobController::class, 'index'])->name('cronjobs.index');
    Route::get('/crear-cronjob', [CronJobController::class, 'create'])->name('crear-cronjob');
    Route::post('/crear-cronjob', [CronJobController::class, 'store'])->name('store-cronjob');
    Route::get('/editar-cronjob/{id}', [CronJobController::class, 'edit'])->name('editar-cronjob');
    Route::put('/editar-cronjob/{id}', [CronJobController::class, 'update'])->name('actualizar-cronjob');
    Route::delete('/eliminar-cronjob/{id}', [CronJobController::class, 'destroy'])->name('eliminar-cronjob');

    Route::get('/ventas', [VentasController::class, 'index'])->name('ventas.index');
    Route::get('/usuario-venta/{id}', [VentasController::class, 'show'])->name('ver-usuario');
    Route::get('/ventas-export', [VentasController::class, 'exportVentas'])->name('ventas.exportVentas');
    Route::get('/ventas-import-form', [VentasController::class, 'formImportVentas'])->name('ventas.formImportVentas');
    Route::post('/ventas-import', [VentasController::class, 'importVentas'])->name('ventas.importVentas');

    // Aseguradoras
    Route::get('/aseguradoras', [InsuranceController::class, 'index'])->name('aseguradoras.index');
    Route::get('/crear-aseguradora', [InsuranceController::class, 'create'])->name('crear-aseguradora');
    Route::post('/crear-aseguradora', [InsuranceController::class, 'store'])->name('store-aseguradora');
    Route::get('/editar-aseguradora/{id}', [InsuranceController::class, 'edit'])->name('editar-aseguradora');
    Route::put('/editar-aseguradora/{id}', [InsuranceController::class, 'update'])->name('actualizar-aseguradora');
    Route::delete('/eliminar-aseguradora/{id}', [InsuranceController::class, 'destroy'])->name('eliminar-aseguradora');

    // Rutas Campañas
    Route::resource('/campaigns', CampaignController::class);
});

// Formulario de prueba para insercion de datos
Route::get('/form', [VentasController::class, 'form'])->name('ventas.form');
