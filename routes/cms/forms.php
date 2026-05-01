<?php

use App\Http\Controllers\Cms\ContactFormController;
use App\Http\Controllers\Cms\HumanResourceController;
use App\Http\Controllers\Cms\RandevuAlFormController;
use Illuminate\Support\Facades\Route;

Route::prefix('/forms')->name('forms.')->group(function () {
    # İletişim Formları
    Route::get('/contact-forms', [ContactFormController::class, 'iletisimFormu'])->name('iletisimFormu');
    Route::get('/get-imail', [ContactFormController::class, 'getMail'])->name('getImail');

    Route::get('/human-resources-forms', [HumanResourceController::class, 'insanKaynaklariFormu'])->name('insanKaynaklariFormu');
    Route::get('/get-Imail', [HumanResourceController::class, 'getIkMail'])->name('getIkmail');

    Route::get('/randevu-al-forms', [RandevuAlFormController::class, 'randevuAlFormu'])->name('randevuAlFormu');
    Route::get('/get-rmail', [RandevuAlFormController::class, 'getRMail'])->name('getRMail');
});


