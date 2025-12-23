<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountTypesController;
use App\Http\Controllers\JournalEntriesController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/tipe_akun', [AccountTypesController::class, 'index'])->name('tipe_akun');
Route::get('/akun', [AccountController::class, 'index'])->name('akun');

Route::get('/jurnal_umum', [JournalsController::class, 'index'])->name('jurnal_umum');
Route::post('/jurnal_umum/store', [JournalsController::class, 'store'])->name('jurnal_umum.store');
Route::put('/jurnal_umum/{id}/update', [JournalsController::class, 'update'])->name('jurnal_umum.update');
Route::delete('/jurnal_umum/{id}/destroy', [JournalsController::class, 'destroy'])->name('jurnal_umum.destroy');

Route::get('/riwayat_jurnal', [JournalEntriesController::class, 'index'])->name('riwayat_jurnal');
