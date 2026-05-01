<?php

use App\Http\Controllers\Cms\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UIndexController;
use App\Http\Controllers\User\ULanguageController;



//Panel Yonetim İşlemleri
Route::prefix('yonetim')->name("cms.")->middleware('loginPageAuthControl')->group(function () {


//    Giriş Gerektirmeyen Auth İşlemleri
    require __DIR__ . '/noAuth.php';


//    Giriş Gerektiren İşlemler
    Route::middleware('auth')->group(function () {

        require __DIR__ . '/auth.php';

        Route::get('', [IndexController::class, 'index'])->name('dashboard');
        Route::post('/slug-maker', [IndexController::class, 'slugMaker'])->name('slug.maker');

        require __DIR__ . '/cms/user.php';
        require __DIR__ . '/cms/permissions.php';
        require __DIR__ . '/cms/rolePermission.php';
        require __DIR__ . '/cms/roles.php';
        require __DIR__ . '/cms/roleUser.php';
        require __DIR__ . '/cms/languages.php';
        require __DIR__ . '/cms/blades.php';
        require __DIR__ . '/cms/karnavalSezonu.php';
        require __DIR__ . '/cms/categories.php';
        require __DIR__ . '/cms/pages.php';
        require __DIR__ . '/cms/doctors.php';
        require __DIR__ . '/cms/seos.php';
        require __DIR__ . '/cms/contacts.php';
        require __DIR__ . '/cms/socialMedia.php';
        require __DIR__ . '/cms/forms.php';
        require __DIR__ . '/cms/slider.php';
        require __DIR__ . '/cms/galleries.php';
        require __DIR__ . '/cms/popup.php';
        require __DIR__ . '/cms/features.php';
        require __DIR__ . '/cms/references.php';
        require __DIR__ . '/cms/referenceTypes.php';
        require __DIR__ . '/cms/faq.php';
        require __DIR__ . '/cms/certificates.php';
        require __DIR__ . '/cms/pressKit.php';
        require __DIR__ . '/cms/sideMenuElements.php';
        require __DIR__ . '/cms/comments.php';

        Route::prefix('settings')->name("settings.")->group(function () {
            require __DIR__ . '/cms/siteSettings.php';
        });

    });


});
Route::get('/test-geo', function () {
    $userAgent = request()->userAgent();
    $isBot = false;

    // AI Bot kontrolü (GeoMiddleware'deki gibi)
    $aiBots = ['chatgpt-user', 'gptbot', 'claude-web', 'anthropic-ai'];
    foreach ($aiBots as $bot) {
        if (str_contains(strtolower($userAgent), $bot)) {
            $isBot = true;
            break;
        }
    }

    return response()->json([
        'status' => 'GEO Middleware Aktif',
        'user_agent' => $userAgent,
        'is_ai_bot' => $isBot,
        'timestamp' => now()->format('Y-m-d H:i:s'),
        'headers' => [
            'X-Robots-Tag' => $isBot ? 'Eklendi' : 'Eklenmedi',
            'X-Content-Type' => $isBot ? 'Eklendi' : 'Eklenmedi'
        ]
    ]);
});
Route::get('', [UIndexController::class, 'index'])->name('home');
Route::controller(UIndexController::class)->group(base_path('routes/user/index.php'));
Route::post('/arama-kayit', [UIndexController::class, 'aramaKayit'])->name('aramaKayit');
Route::get('/lang/switch/{langId}', [ULanguageController::class, 'switchLanguage'])->name('user.switchLanguage');
// Dinamik Sitemap
Route::get('/sitemap.xml', [UIndexController::class, 'sitemap'])->name('sitemap');



