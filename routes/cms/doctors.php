<?php

use App\Http\Controllers\Cms\DoctorController;
use Illuminate\Support\Facades\Route;

Route::get('/doctors/deleted', [DoctorController::class, 'deleted'])->name('doctors.deleted');
Route::delete('doctors/{id}/force-delete', [DoctorController::class, 'forceDelete'])->name('doctors.forceDelete');
Route::post('doctors/{id}/restore', [DoctorController::class, 'restore'])->name('doctors.restore');
Route::post('doctors/{id}/publish',[DoctorController::class,"publishPage"])->name("doctors.publish");
Route::post('doctors/{id}/activate',[DoctorController::class,"activate"])->name("doctors.activate");
Route::resource('doctors', DoctorController::class)->only(['index', 'create', 'edit', 'update', 'store', 'destroy']);


