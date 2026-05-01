<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class ULanguageController extends Controller
{
    public function switchLanguage($langId)
    {
        // 1. Hedef Dili Kontrol Et
        $targetLang = Language::find($langId);
        if (!$targetLang) {
            return Redirect::back();
        }

        // 2. Session ve App Locale Ayarla
        Session::put('uLangId', $targetLang->id);
        Session::put('uLang', $targetLang->code);
        App::setLocale($targetLang->code);

        // 3. Kullanıcının geldiği URL'i (Slug'ı) al
        $previousUrl = URL::previous();
        $path = parse_url($previousUrl, PHP_URL_PATH);
        $currentSlug = ltrim($path, '/'); // Örn: "/hakkimizda" -> "hakkimizda"

        // Anasayfadaysa direkt yeni dilin anasayfasına git
        if (empty($currentSlug) || $currentSlug == '/') {
            return Redirect::route('home');
        }

        // 4. Slug bir SAYFA mı?
        $page = Page::where('slug', $currentSlug)->first();

        if ($page) {
            // Sayfanın hedef dildeki karşılığını bul
            $translatedPage = $page->getTranslationFor($targetLang->id);

            if ($translatedPage) {
                // Çeviri bulundu, oraya yönlendir
                return Redirect::to($translatedPage->slug);
            }
        }

        // 5. Çeviri bulunamadıysa, hedef dilin anasayfasına yönlendir
        return Redirect::route('home');
    }
}
