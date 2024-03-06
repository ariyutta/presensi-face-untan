<?php

use App\Http\Controllers\API\KehadiranController as APIKehadiranController;
use App\Http\Controllers\API\PegawaiController as APIPegawaiController;
use App\Http\Controllers\API\UnitController as APIUnitController;
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

Route::prefix('unit')->name('unit.')->group(function () {
    Route::get('/', [APIUnitController::class, 'index'])->name('index');
    Route::get('/{idUnit}', [APIUnitController::class, 'detail'])->name('detail');
});

Route::prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/', [APIPegawaiController::class, 'index'])->name('index');
    Route::get('/{idUnit}', [APIPegawaiController::class, 'detail'])->name('detail');
});

Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
    Route::get('/', [APIKehadiranController::class, 'index'])->name('index');
    Route::get('/{idPegawai}', [APIKehadiranController::class, 'detail'])->name('detail');
});

Route::prefix('transaksi-kehadiran')->name('transaksi-kehadiran.')->group(function () {
    Route::get('/', [APIKehadiranController::class, 'transaksi_kehadiran'])->name('transaksi_kehadiran');
});
