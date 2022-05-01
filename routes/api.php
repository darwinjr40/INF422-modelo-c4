<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('user',[ProyectoController::class, 'user'])->name('proyecto.user');
Route::post('guardar-diagrama',[ProyectoController::class, 'guardar'])->name('proyecto.guardar');
Route::get('cargar-diagrama',[ProyectoController::class, 'cargarDiagrama'])->name('proyecto.cargar');
