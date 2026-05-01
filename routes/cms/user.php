<?php

use App\Http\Controllers\Cms\RoleController;
use App\Http\Controllers\Cms\UserController;
use Illuminate\Support\Facades\Route;


Route::get("users",[UserController::class,"index"])->name("users.index");


