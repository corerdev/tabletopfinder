<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitasController;
use App\Http\Controllers\TipoLavadoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
     return $request->user();
});


Route::post('/citas/api',[CitasController::class, 'api'])->name('citas.api');

Route::post('/tipoLavado/eliminarLavado',[TipoLavadoController::class, 'eliminarLavado'])->name('tipoLavado.eliminarLavado');
Route::post('/tipoLavado/getListado',[TipoLavadoController::class, 'getListado'])->name('tipoLavado.getListado');

Route::post('/tipoLavado/validarDescripcion',[TipoLavadoController::class, 'validarDescripcion'])->name('tipoLavado.validarDescripcion');
Route::post('/tipoLavado/validarTiempo',[TipoLavadoController::class, 'validarTiempo'])->name('tipoLavado.validarTiempo');
Route::post('/tipoLavado/validarPrecio',[TipoLavadoController::class, 'validarPrecio'])->name('tipoLavado.validarPrecio');
Route::post('/tipoLavado/registrarLavado',[TipoLavadoController::class, 'registrarLavado'])->name('tipoLavado.registrarLavado');
