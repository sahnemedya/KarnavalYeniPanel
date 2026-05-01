<?php

use App\Http\Controllers\Cms\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/category/deleted', [CategoryController::class, 'deleted'])->name('category.deleted');
Route::post('category/{id}/activate', [CategoryController::class, 'activate'])->name('category.activate');
Route::delete('category/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('category.forceDelete');
Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
Route::resource('category', CategoryController::class);
