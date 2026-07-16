<?php

use App\Http\Controllers\Cms\AwardController;
use Illuminate\Support\Facades\Route;

Route::post("awards/{id}/publish", [AwardController::class, 'publish'])->name('awards.publish');
Route::resource('awards', AwardController::class);
