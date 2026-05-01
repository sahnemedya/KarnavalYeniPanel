<?php

use App\Http\Controllers\Cms\FeaturesController;
use Illuminate\Support\Facades\Route;

Route::get('features/create/{page_id}', [FeaturesController::class, 'create'])->name('cms.features.create');
Route::post("features/{id}/publish", [FeaturesController::class, 'publish'])->name('features.publish');
Route::get("features/deleted", [FeaturesController::class, 'deleted'])->name('features.deleted');
Route::post('features/{id}/restore', [FeaturesController::class, "restore"])->name("features.restore");
Route::delete('features/{id}/force-delete', [FeaturesController::class, "forceDelete"])->name("features.forceDelete");
Route::resource('features', FeaturesController::class);


