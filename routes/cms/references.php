<?php

use App\Http\Controllers\Cms\ReferencesController;
use Illuminate\Support\Facades\Route;

Route::get('references/bulk-create', [ReferencesController::class, 'bulkCreate']) ->name('references.bulk-create');
Route::post('references/bulk-store', [ReferencesController::class, 'bulkStore'])->name('references.bulk-store');
Route::post("references/{id}/publish", [ReferencesController::class, 'publish'])->name('references.publish');
Route::get("references/deleted", [ReferencesController::class, 'deleted'])->name('references.deleted');
Route::post('references/{id}/restore', [ReferencesController::class, "restore"])->name("references.restore");
Route::delete('references/{id}/force-delete', [ReferencesController::class, "forceDelete"])->name("references.forceDelete");
Route::post("references/{id}/showHomePage", [ReferencesController::class, 'showHomePage'])->name('references.showHomePage');
Route::resource('references', ReferencesController::class);


