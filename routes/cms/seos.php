<?php

use App\Http\Controllers\Cms\SeoController;
use Illuminate\Support\Facades\Route;


Route::resource('seos', SeoController::class);


