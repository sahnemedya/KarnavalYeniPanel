<?php

use App\Http\Controllers\Cms\PermissionController;
use Illuminate\Support\Facades\Route;


Route::resource('permission', PermissionController::class)->only("index", "create", "store", "edit", "update", "destroy");


