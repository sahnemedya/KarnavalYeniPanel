<?php

use App\Http\Controllers\Cms\PopupController;
use Illuminate\Support\Facades\Route;

Route::post("popup/{id}/publish", [PopupController::class, 'publish'])->name('popup.publish');
Route::resource('popup', PopupController::class);


