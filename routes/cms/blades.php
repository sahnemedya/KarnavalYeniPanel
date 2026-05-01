<?php

use App\Http\Controllers\Cms\BladeController;
use Illuminate\Support\Facades\Route;


Route::resource('blades', BladeController::class)->only(['index', 'create', 'store','destroy']);


