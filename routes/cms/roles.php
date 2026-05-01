<?php

use App\Http\Controllers\Cms\RoleController;
use Illuminate\Support\Facades\Route;


Route::resource('roles', RoleController::class)->only("index", "create", "store", "edit", "update", "destroy");


