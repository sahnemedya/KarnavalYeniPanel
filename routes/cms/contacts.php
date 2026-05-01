<?php

use App\Http\Controllers\Cms\ContactsController;
use Illuminate\Support\Facades\Route;

Route::get("contacts/deleted", [ContactsController::class, 'deleted'])->name('contacts.deleted');
Route::post("contacts/{id}/restore", [ContactsController::class, 'restore'])->name('contacts.restore');
Route::post("contacts/{id}/force-delete", [ContactsController::class, 'forceDelete'])->name('contacts.forceDelete');
Route::resource('contacts', ContactsController::class);


