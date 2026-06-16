<?php

use App\Http\Controllers\Cms\PodcastController;
use Illuminate\Support\Facades\Route;

Route::resource('podcast', PodcastController::class);


