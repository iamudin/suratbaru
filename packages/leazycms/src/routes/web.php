<?php

use Illuminate\Support\Facades\Route;
use Leazycms\Web\Http\Controllers\WebController;
use Leazycms\Web\Http\Controllers\SuratController;
Route::match(['get', 'post'],'surat-keluar/{keyword}', [SuratController::class, 'index']);
Route::match(['get', 'post'],'qr_surat/{keyword}', [SuratController::class, 'qr_surat']);
Route::match(['get', 'post'],'generate/{keyword}', [SuratController::class, 'generate_surat'])->middleware('auth');
Route::match(['get', 'post'],'/', [WebController::class, 'home']);




