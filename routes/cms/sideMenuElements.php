<?php

use App\Http\Controllers\Cms\SideMenuElementController;
use Illuminate\Support\Facades\Route;

Route::post('side-menu-elements/page/{pageId}/videos',
    [SideMenuElementController::class, 'videoStore']
)->name('side-menu-elements.videoStore');

Route::put('side-menu-elements/videos/{videoId}',
    [SideMenuElementController::class, 'videoUpdate']
)->name('side-menu-elements.videoUpdate');

Route::delete('side-menu-elements/videos/{videoId}',
    [SideMenuElementController::class, 'videoDestroy']
)->name('side-menu-elements.videoDestroy');

// Sürükle-bırak sıralama
Route::post('side-menu-elements/videos/reorder',
    [SideMenuElementController::class, 'videoReorder']
)->name('side-menu-elements.videoReorder');

// CHUNKED UPLOAD (Büyük video dosyaları için)
Route::post('side-menu-elements/videos/upload/init',
    [SideMenuElementController::class, 'videoUploadInit']
)->name('side-menu-elements.videoUploadInit');

Route::post('side-menu-elements/videos/upload/{uploadId}/chunk',
    [SideMenuElementController::class, 'videoUploadChunk']
)->name('side-menu-elements.videoUploadChunk');

Route::post('side-menu-elements/videos/upload/{uploadId}/finalize',
    [SideMenuElementController::class, 'videoUploadFinalize']
)->name('side-menu-elements.videoUploadFinalize');

Route::delete('side-menu-elements/videos/upload/{uploadId}',
    [SideMenuElementController::class, 'videoUploadCancel']
)->name('side-menu-elements.videoUploadCancel');

// Kapak görseli (küçük dosya, mevcut asyncUpload pattern'i)
Route::post('side-menu-elements/videos/cover-upload',
    [SideMenuElementController::class, 'videoCoverUpload']
)->name('side-menu-elements.videoCoverUpload');

// Geçici dosya silme (form'dan vazgeçince)
Route::post('side-menu-elements/videos/temp-delete',
    [SideMenuElementController::class, 'videoTempDelete']
)->name('side-menu-elements.videoTempDelete');
Route::post("side-menu-elements/{id}/publish", [SideMenuElementController::class, 'showHomePage'])->name('side-menu-elements.showHomePage');
Route::post("side-menu-elements/{id}/add-menus", [SideMenuElementController::class, 'showMenu'])->name('side-menu-elements.showMenu');

// EXTRA ALAN VE ASENKRON DOSYA YÜKLEME ROTALARI (YENİ)
Route::get('side-menu-elements/extra/{id}', [SideMenuElementController::class, 'extraedit'])->name('side-menu-elements.extraedit');
Route::put('side-menu-elements/extraStoreUpdate/{id}', [SideMenuElementController::class, 'extraStoreUpdate'])->name('side-menu-elements.extraStoreUpdate');
Route::post('side-menu-elements/async-upload', [SideMenuElementController::class, 'asyncUpload'])->name('side-menu-elements.asyncUpload');
Route::post('side-menu-elements/async-delete', [SideMenuElementController::class, 'asyncDelete'])->name('side-menu-elements.asyncDelete');

Route::get('side-menu-elements/{id}', [SideMenuElementController::class, 'index'])->name('side-menu-elements.index');
Route::get('side-menu-elements/create/{id}', [SideMenuElementController::class, 'create'])->name('side-menu-elements.create');
Route::post('side-menu-elements', [SideMenuElementController::class, 'store'])->name('side-menu-elements.store');
Route::get('side-menu-elements/{categoryId}/{pageId}/edit', [SideMenuElementController::class, 'edit'])->name('side-menu-elements.edit');
Route::put('side-menu-elements/{pageId}', [SideMenuElementController::class, 'update'])->name('side-menu-elements.update');
Route::get('/side-menu-elements/{categoryId}/deleted', [SideMenuElementController::class, 'deleted'])->name('side-menu-elements.deleted');

Route::get('side-menu-elements/{categoryId}/page/{pageId}/create-language', [SideMenuElementController::class, 'createLanguage'])->name('side-menu-elements.createLanguage');
Route::post('get-data-by-lang', [SideMenuElementController::class, 'getDataByLang'])->name('get.data.by.lang');
Route::post('side-menu-elements/fetch-translation', [SideMenuElementController::class, 'fetchTranslationFromGemini'])->name('fetch.translation');


