<?php

use App\Http\Controllers\Cms\CorporateIdentityControllers;
use Illuminate\Support\Facades\Route;

Route::post("corporate_identity/{id}/publish", [CorporateIdentityControllers::class, 'publish'])->name('corporateIdentity.publish');
Route::resource('corporateIdentity', CorporateIdentityControllers::class);


