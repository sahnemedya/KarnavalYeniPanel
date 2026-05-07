<?php

use App\Http\Controllers\Cms\BalkonVitrinBasvuruController;
use App\Http\Controllers\Cms\ContactFormController;
use App\Http\Controllers\Cms\HumanResourceController;
use App\Http\Controllers\Cms\BultenIletisimFormController;
use App\Http\Controllers\Cms\PortakalliLezzetlerFormController;
use Illuminate\Support\Facades\Route;

Route::prefix('/forms')->name('forms.')->group(function () {
    # İletişim Formları
    Route::get('/contact-forms', [ContactFormController::class, 'iletisimFormu'])->name('iletisimFormu');
    Route::get('/get-imail', [ContactFormController::class, 'getMail'])->name('getImail');

    Route::get('/balkon-vitrin-basvuru', [BalkonVitrinBasvuruController::class, 'yarismaBasvurulari'])->name('balkonVitrinBasvurulari');
    Route::get('/get-bmail', [BalkonVitrinBasvuruController::class, 'getYarismaDetay'])->name('getBmail');

    Route::get('/bulten-aboneligi', [BultenIletisimFormController::class, 'bultenFormu'])->name('bultenFormu');
    Route::get('/get-bultenmail', [BultenIletisimFormController::class, 'getBultenDetay'])->name('getBultenDetay');

    Route::get('/portakalli-lezzetler', [PortakalliLezzetlerFormController::class, 'portakalliLezzetler'])->name('portakalliLezzetler');
    Route::get('/get-pmail', [PortakalliLezzetlerFormController::class, 'getPortakalliLezzetler'])->name('getPortakalliLezzetler');

    Route::get('/human-resources-forms', [HumanResourceController::class, 'insanKaynaklariFormu'])->name('insanKaynaklariFormu');
    Route::get('/get-Imail', [HumanResourceController::class, 'getIkMail'])->name('getIkmail');
});


