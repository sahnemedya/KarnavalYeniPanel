<?php

use App\Http\Controllers\Cms\PageController;
use Illuminate\Support\Facades\Route;

Route::post("pages/{id}/showHomePage", [PageController::class, 'showHomePage'])->name('pages.showHomePage');
Route::get('/pages/deleted', [PageController::class, 'deleted'])->name('pages.deleted');
Route::get('pages/extra/{id}', [PageController::class, 'extraedit'])->name('pages.extraedit');
Route::put('pages/extraStoreUpdate/{id}', [PageController::class, 'extraStoreUpdate'])->name('pages.extraStoreUpdate');
Route::post('pages/{id}/publish', [PageController::class, "publishPage"])->name("pages.publish");
Route::post('pages/{id}/activate', [PageController::class, "activate"])->name("pages.activate");
Route::post('pages/{id}/restore', [PageController::class, "restore"])->name("pages.restore");
Route::delete('pages/{id}/force-delete', [PageController::class, "forceDelete"])->name("pages.forceDelete");

// ASENKRON DOSYA YÜKLEME VE SİLME ROTALARI (DÜZELTİLDİ)
Route::post('pages/async-upload', [PageController::class, 'asyncUpload'])->name('pages.asyncUpload');
Route::post('pages/async-delete', [PageController::class, 'asyncDelete'])->name('pages.asyncDelete');

Route::resource('pages', PageController::class);
