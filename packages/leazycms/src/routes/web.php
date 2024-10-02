<?php

use Illuminate\Support\Facades\Route;
use Leazycms\Web\Http\Controllers\WebController;
use Leazycms\Web\Http\Controllers\SuratController;
Route::match(['post'],'cek_surat', [SuratController::class, 'cek_surat']);
Route::match(['post'],'upload_docx', [SuratController::class, 'upload_docx']);
Route::match(['get', 'post'],'surat-keluar/{keyword}', [SuratController::class, 'index']);
Route::match(['get', 'post'],'qr_surat/{keyword}', [SuratController::class, 'qr_surat']);
Route::match(['get', 'post'],'generate/{keyword}', [SuratController::class, 'generate_surat'])->middleware('auth');




