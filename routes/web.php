<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('profile', [HomeController::class, 'profile'])->name('profile');
Route::get('qr', [QrController::class, 'index'])->name('qr.index');
Route::post('qr/preview', [QrController::class, 'previewAjax'])->name('qr.preview.ajax');
Route::post('qr/generate', [QrController::class, 'generate'])->name('qr.generate');
