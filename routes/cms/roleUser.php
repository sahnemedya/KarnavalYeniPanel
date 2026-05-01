<?php

use App\Http\Controllers\Cms\RoleUserController;
use Illuminate\Support\Facades\Route;


Route::resource('role-user', RoleUserController::class)->only("index", "create", "store", "edit", "update", "destroy");


