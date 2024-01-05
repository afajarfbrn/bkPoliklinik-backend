<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group ([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('admin/', [App\Http\Controllers\AdminController::class, 'store']);
    Route::post('admin/login', [App\Http\Controllers\AdminController::class, 'login'])->name('login');
    Route::post('admin/logout', [App\Http\Controllers\AdminController::class, 'logout']);
    Route::post('admin/me', [App\Http\Controllers\AdminController::class, 'me']);
});

Route::group ([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::get('dokter/getAll', [App\Http\Controllers\DokterController::class, 'index']);
    Route::get('dokter/get/{id}', [App\Http\Controllers\DokterController::class, 'show']);
    Route::put('dokter/update/{id}', [App\Http\Controllers\DokterController::class, 'update']);
    Route::delete('dokter/delete/{id}', [App\Http\Controllers\DokterController::class, 'destroy']);
    Route::post('dokter/', [App\Http\Controllers\DokterController::class, 'store']);
    Route::post('dokter/login', [App\Http\Controllers\DokterController::class, 'login'])->name('login');
    Route::post('dokter/logout', [App\Http\Controllers\DokterController::class, 'logout']);
    Route::post('dokter/me', [App\Http\Controllers\DokterController::class, 'me']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'obat'
], function ($router) {
    Route::get('/', [App\Http\Controllers\ObatController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\ObatController::class, 'show']);
    Route::post('/', [App\Http\Controllers\ObatController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\ObatController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\ObatController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('pasien/login', [App\Http\Controllers\PasienController::class, 'login'])->name('login');
    Route::post('pasien/', [App\Http\Controllers\PasienController::class, 'register']);
    Route::post('pasien/logout', [App\Http\Controllers\PasienController::class, 'logout']);
    Route::post('pasien/me', [App\Http\Controllers\PasienController::class, 'me']);
    Route::get('pasien/getAll', [App\Http\Controllers\PasienController::class, 'index']);
    Route::get('pasien/get/{id}', [App\Http\Controllers\PasienController::class, 'show']);
    Route::put('pasien/update/{id}', [App\Http\Controllers\PasienController::class, 'update']);
    Route::delete('pasien/delete/{id}', [App\Http\Controllers\PasienController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'poli'
], function ($router) {
    Route::get('/', [App\Http\Controllers\PoliController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\PoliController::class, 'show']);
    Route::post('/', [App\Http\Controllers\PoliController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\PoliController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\PoliController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'jadwal_periksa'
], function ($router) {
    Route::get('/', [App\Http\Controllers\JadwalPeriksaController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\JadwalPeriksaController::class, 'show']);
    Route::post('/', [App\Http\Controllers\JadwalPeriksaController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\JadwalPeriksaController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\JadwalPeriksaController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'daftar_poli'
], function ($router) {
    Route::get('/', [App\Http\Controllers\DaftarPoliController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\DaftarPoliController::class, 'show']);
    Route::post('/', [App\Http\Controllers\DaftarPoliController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\DaftarPoliController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\DaftarPoliController::class, 'destroy']);
    Route::get('/pasien/{id}', [App\Http\Controllers\DaftarPoliController::class, 'showByPasienId']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'periksa'
], function ($router) {
    Route::get('/', [App\Http\Controllers\PeriksaController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\PeriksaController::class, 'show']);
    Route::post('/', [App\Http\Controllers\PeriksaController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\PeriksaController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\PeriksaController::class, 'destroy']);
    Route::get('/daftar_poli/{id}', [App\Http\Controllers\PeriksaController::class, 'showByDafPolId']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'detail_periksa'
], function ($router) {
    Route::get('/', [App\Http\Controllers\DetailPeriksaController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\DetailPeriksaController::class, 'show']);
    Route::post('/', [App\Http\Controllers\DetailPeriksaController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\DetailPeriksaController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\DetailPeriksaController::class, 'destroy']);   
    Route::get('/periksa/{id}', [App\Http\Controllers\DetailPeriksaController::class, 'showByPeriksaId']);
    Route::delete('/obat/{id}/{idPeriksa}', [App\Http\Controllers\DetailPeriksaController::class, 'destroyByObatId']);
});