<?php

use App\Http\Controllers\Cms\LanguageController;
use Illuminate\Support\Facades\Route;


Route::resource('languages', LanguageController::class)->only(['index', 'create', 'store','destroy']);
Route::put('languages/activate/{id}', [LanguageController::class, 'activate'])->name('languages.activate');

