<?php

use App\Http\Controllers\Cms\ApiKeyController;
use App\Http\Controllers\Cms\SettingController;
use App\Http\Controllers\Cms\PanelMenuSettingController;
use Illuminate\Support\Facades\Route;


Route::resource('site', SettingController::class)->only(['index', 'update'])->middleware("permission:ayarlar");
Route::resource('api-key', ApiKeyController::class)->only(['index', 'update'])->middleware("permission:ayarlar");
Route::resource('panel-menu',PanelMenuSettingController::class)->only(['index', 'update'])->middleware("permission:ayarlar");


