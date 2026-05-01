<?php

use App\Http\Controllers\Cms\PressKitController;
use Illuminate\Support\Facades\Route;

Route::post("press-kit/{id}/publish", [PressKitController::class, 'publish'])->name('press-kit.publish');
Route::resource('press-kit', PressKitController::class);


