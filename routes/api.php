<?php

use App\Http\Controllers\API\Educacion\EducationController;
use App\Http\Controllers\API\ventas\VentasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('users', function () {
    $users = App\Models\User::all();
    return response()->json($users);
});

Route::post('/ventas-nuevas', [VentasController::class, 'store']);
Route::post('/ventas-educacion', [EducationController::class, 'store']);