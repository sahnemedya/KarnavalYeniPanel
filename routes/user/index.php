
<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UIndexController;

Route::get('', 'index')->name('home');
Route::get('/sitemap.xml', [UIndexController::class, 'sitemap'])->name('sitemap');
Route::post('/arama-kayit', [UIndexController::class, 'aramaKayit'])->name('aramaKayit');

Route::post('/sayfa-ara', [UIndexController::class, 'aramaSonucu'])->name('pageSearch');
Route::get('/sayfa-ara', [UIndexController::class, 'aramaGet'])->name('pageSearchGet');


//Route::get('/sertifika-sorgula/{sno}', [UIndexController::class, 'sertifikaSorgula'])->name('sertifikaSorgula');
//Route::post('/sertifika-sorgula', [UIndexController::class, 'sertifikaSorgula'])->name('sertifikaSorgulaPost');




//Route::post('/iletisim-formu', [UIndexController::class, 'iletisimPost'])->name('iletisimPost');
Route::post('/iletisim', [UIndexController::class, 'iletisimPost'])->name('iletisimPost');
Route::post('/bulten-post', [UIndexController::class, 'bultenIletisimPost'])->name('bultenPost');
Route::post('/protakalli-lezzetler-yarisma-basvuru-post', [UIndexController::class, 'portakalliLezzetlerPost'])->name('portakalliLezzetlerPost');
Route::post('/balkon-vitrin-yarisma-post', [UIndexController::class, 'yarismaBasvuruPost'])->name('yarismaBasvuruPost');
Route::post('/insan-kaynaklari-gonder', [UIndexController::class, 'insanKaynaklariPost'])->name('insanKaynaklariPost');
Route::post('/on-kayit-yap', [UIndexController::class, 'onKayitPost'])->name('onKayitPost');







Route::get('/{slug}', [UIndexController::class, 'slug'])->name('siteUrl');
