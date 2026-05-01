<?php

use App\Http\Controllers\Cms\ConsentFormsController;
use Illuminate\Support\Facades\Route;

Route::post("consent-forms/{id}/publish", [ConsentFormsController::class, 'publish'])->name('consent-forms.publish');
Route::resource('consent-forms', ConsentFormsController::class);


