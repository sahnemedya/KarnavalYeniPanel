<?php

use App\Http\Controllers\Cms\KarnavalSezonuController;
use Illuminate\Support\Facades\Route;


Route::get('karnaval-sezonu/create',[KarnavalSezonuController::class, 'create'])->name('karnaval-sezonu.create');
Route::post('karnaval-sezonu/{id}/activate',[KarnavalSezonuController::class,"activate"])->name("karnaval-sezonu.activate");
Route::post('karnaval-sezonu/{id}/restore',[KarnavalSezonuController::class,"restore"])->name("karnaval-sezonu.restore");
Route::get('/karnaval-sezonu/deleted', [KarnavalSezonuController::class, 'deleted'])->name('karnaval-sezonu.deleted');
Route::delete('karnaval-sezonu/{id}/force-delete',[KarnavalSezonuController::class,"forceDelete"])->name("karnaval-sezonu.forceDelete");
Route::resource('karnaval-sezonu', KarnavalSezonuController::class);
