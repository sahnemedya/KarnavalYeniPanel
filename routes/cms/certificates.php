<?php

use App\Http\Controllers\Cms\CertificateController;
use Illuminate\Support\Facades\Route;

Route::post("certificate/{id}/publish", [CertificateController::class, 'publish'])->name('certificate.publish');
Route::resource('certificate', CertificateController::class);


