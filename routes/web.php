<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
});

Route::prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/', [PegawaiController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
    Route::get('/get-data', [PegawaiController::class, 'getData'])->middleware(['auth', 'verified'])->name('getData');
});

Route::prefix('kehadiran')->name('kehadiran.')->group(function () {
    Route::get('/', [KehadiranController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
    Route::get('/get-data', [KehadiranController::class, 'getData'])->middleware(['auth', 'verified'])->name('getData');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/kehadiran/home', [KehadiranController::class, 'newIndex'])->name('kehadiran.home');
Route::post('/kehadiran/data', [KehadiranController::class, 'newData'])->name('kehadiran.data');

require __DIR__ . '/auth.php';
