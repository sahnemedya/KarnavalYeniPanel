<?php

use App\Http\Controllers\Cms\SliderController;
use Illuminate\Support\Facades\Route;

Route::post("slider/{id}/publish", [SliderController::class, 'publish'])->name('slider.publish');
Route::resource('slider', SliderController::class);


