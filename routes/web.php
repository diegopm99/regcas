<?php

use App\Http\Controllers\ComprobanteCabeceraController;
use App\Http\Controllers\ComprobanteDocumentoController;
use App\Http\Controllers\CuentaContableController;
use App\Http\Controllers\FuenteFtoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RetencionSunatController;
use App\Http\Controllers\TipoDocumentoController;
use Illuminate\Support\Facades\Route;

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
    return view('home');
});

Route::get('/regcas_comprobante', [ComprobanteCabeceraController::class, 'index'])->name('comprobante.index');
Route::get('/regcas_comprobante/find', [ComprobanteCabeceraController::class, 'find']);
Route::get('/regcas_fuente/filter/{anoproc}', [FuenteFtoController::class, 'filterByAnoProc'])->whereNumber('anoproc');

Route::get('/regcas_cpdocs', [ComprobanteDocumentoController::class, 'view'])->name('documento.view');
Route::get('/regcas_cpdocs/index', [ComprobanteDocumentoController::class, 'index']);
Route::get('/regcas_cpdocs/filter', [ComprobanteDocumentoController::class, 'filterByComprobante']);
Route::post('/regcas_cpdocs/store', [ComprobanteDocumentoController::class, 'store']);
Route::post('/regcas_cpdocs/update', [ComprobanteDocumentoController::class, 'update']);
Route::delete('/regcas_cpdocs/destroy/{id}', [ComprobanteDocumentoController::class, 'destroy'])->whereNumber('id');
Route::post('/regcas_cpdocs/restore/{id}', [ComprobanteDocumentoController::class, 'restore'])->whereNumber('id');

Route::get('/regcas_ctas_conts', [CuentaContableController::class, 'view'])->name('cuenta.view');
Route::get('/regcas_ctas_conts/index', [CuentaContableController::class, 'index']);
Route::get('/regcas_ctas_conts/indexWithTrashed', [CuentaContableController::class, 'indexWithTrashed']);
Route::get('/regcas_ctas_conts/filter/{tipo}', [CuentaContableController::class, 'filterByTipoOrden']);
Route::post('/regcas_ctas_conts/store', [CuentaContableController::class, 'store']);
Route::post('/regcas_ctas_conts/update', [CuentaContableController::class, 'update']);
Route::delete('/regcas_ctas_conts/destroy/{id}', [CuentaContableController::class, 'destroy'])->whereNumber('id');
Route::post('/regcas_ctas_conts/restore/{id}', [CuentaContableController::class, 'restore'])->whereNumber('id');

Route::get('/regcas_retenciones', [RetencionSunatController::class, 'view'])->name('retencion.view');
Route::get('/regcas_retenciones/index', [RetencionSunatController::class, 'index']);
Route::get('/regcas_retenciones/indexWithTrashed', [RetencionSunatController::class, 'indexWithTrashed']);
Route::post('/regcas_retenciones/store', [RetencionSunatController::class, 'store']);
Route::post('/regcas_retenciones/update', [RetencionSunatController::class, 'update']);
Route::delete('/regcas_retenciones/destroy/{id}', [RetencionSunatController::class, 'destroy'])->whereNumber('id');
Route::post('/regcas_retenciones/restore/{id}', [RetencionSunatController::class, 'restore'])->whereNumber('id');

Route::get('/regcas_tipdocs', [TipoDocumentoController::class, 'view'])->name('tdocumento.view');
Route::get('/regcas_tipdocs/index', [TipoDocumentoController::class, 'index']);
Route::get('/regcas_tipdocs/indexWithTrashed', [TipoDocumentoController::class, 'indexWithTrashed']);
Route::post('/regcas_tipdocs/store', [TipoDocumentoController::class, 'store']);
Route::post('/regcas_tipdocs/update', [TipoDocumentoController::class, 'update']);
Route::delete('/regcas_tipdocs/destroy/{id}', [TipoDocumentoController::class, 'destroy'])->whereNumber('id');
Route::post('/regcas_tipdocs/restore/{id}', [TipoDocumentoController::class, 'restore'])->whereNumber('id');

Route::post('/regcas_proveedor/store', [ProveedorController::class, 'store']);
Route::post('/regcas_proveedor/update', [ProveedorController::class, 'update']);
