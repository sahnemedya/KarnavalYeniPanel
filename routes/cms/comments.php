<?php

use App\Http\Controllers\Cms\CommentsController;
use Illuminate\Support\Facades\Route;

Route::post("comments/{id}/publish", [CommentsController::class, 'publish'])->name('comments.publish');
Route::get("comments/deleted", [CommentsController::class, 'deleted'])->name('comments.deleted');
Route::post('comments/{id}/restore', [CommentsController::class, "restore"])->name("comments.restore");
Route::delete('comments/{id}/force-delete', [CommentsController::class, "forceDelete"])->name("comments.forceDelete");
Route::post("comments/{id}/showHomePage", [CommentsController::class, 'showHomePage'])->name('comments.showHomePage');
Route::resource('comments', CommentsController::class);


