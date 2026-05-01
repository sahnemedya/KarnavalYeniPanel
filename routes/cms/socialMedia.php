<?php

use App\Http\Controllers\Cms\SocialMediaController;
use Illuminate\Support\Facades\Route;


Route::get('/socialMedia/{contactId}/create', [SocialMediaController::class, 'create'])->name('socialMedia.create');
Route::post('/socialMedia/{contactId}/store', [SocialMediaController::class, 'store'])->name('socialMedia.store');
Route::get('/socialMedia/{id}/edit', [SocialMediaController::class, 'edit'])->name('socialMedia.edit');
Route::put('/socialMedia/{id}/update/', [SocialMediaController::class, 'update'])->name('socialMedia.update');
Route::delete('/socialMedia/{id}/destroy', [SocialMediaController::class, 'destroy'])->name('socialMedia.destroy');


