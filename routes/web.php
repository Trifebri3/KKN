<?php

use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PlaygroundController;
use App\Http\Controllers\ScreenController;
use Illuminate\Support\Facades\Route;

// 1. Layar Tampilan Utama (Screen dengan 2 QR Code)
Route::get('/', [ScreenController::class, 'index'])->name('screen.index');
Route::get('/screen/stats', [ScreenController::class, 'stats'])->name('screen.stats');
Route::get('/screen/qr', [ScreenController::class, 'generateQr'])->name('screen.qr');

// 2. Kunjungan / Presensi (QR Code 1)
Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('kunjungan.store');
Route::get('/kunjungan/quick', [KunjunganController::class, 'quick'])->name('kunjungan.quick');

// 3. Playground & Virtual Assistant / QnA (QR Code 2)
Route::get('/playground', [PlaygroundController::class, 'index'])->name('playground.index');
Route::post('/playground/chat', [PlaygroundController::class, 'chat'])->name('playground.chat');
Route::post('/playground/faq/{id}/like', [PlaygroundController::class, 'likeFaq'])->name('playground.faq.like');
Route::post('/playground/ask', [PlaygroundController::class, 'askQuestion'])->name('playground.ask');
Route::post('/playground/clear-chat', [PlaygroundController::class, 'clearChat'])->name('playground.clear_chat');

// 4. Data Pengunjung & Statistik (Menu Lihat & Cetak PDF)
Route::get('/pengunjung', [PengunjungController::class, 'index'])->name('pengunjung.index');
Route::get('/pengunjung/cetak', [PengunjungController::class, 'cetak'])->name('pengunjung.cetak');
