<?php

use App\Http\Controllers\Cms\ReferenceTypeController;
use Illuminate\Support\Facades\Route;



Route::get("reference-types/deleted", [ReferenceTypeController::class, 'deleted'])->name('reference-types.deleted');
Route::post('reference-types/{id}/restore', [ReferenceTypeController::class, "restore"])->name("reference-types.restore");
Route::delete('reference-types/{id}/force-delete', [ReferenceTypeController::class, "forceDelete"])->name("reference-types.forceDelete");

Route::resource('reference-types', ReferenceTypeController::class);


