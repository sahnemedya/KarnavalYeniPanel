<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\BalkonVitrinYarismaMail;
use App\Mail\BultenIletisimFormuMail;
use App\Mail\ContactFormMail;
use App\Mail\HumanResourceFormMail;
use App\Mail\portakalliLezzetlerFormuMail;
use App\Models\BalkonVitrinBasvuru;
use App\Models\BultenAbone;
use App\Models\BultenIletisimFormu;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Comments;
use App\Models\ContactForm;
use App\Models\Contacts;
use App\Models\FAQ;
use App\Models\Gallery;
use App\Models\HumanResourceForm;
use App\Models\KarnavalSezonu;
use App\Models\PortakalliLezzetler;
use App\Models\References;
use App\Models\SiteSettings;
use App\Models\Slider;
use App\Services\AntiSpamService;
use App\Services\PageSchemasService;
use App\Traits\FormHandlerTrait;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\AramaKayitService;
use App\Http\Requests\AramaKayitRequest;
use App\Services\NumberUsersService;
use App\Services\SeoService;


class UIndexController extends Controller
{


    use FormHandlerTrait;

    // Tüm form ve güvenlik fonksiyonları buradan geliyor.

    protected $messages = [
        'adSoyad.required' => 'Ad Soyad alanı zorunludur.',
        'ulkeKodu.required' => 'Ülke Kodu alanı zorunludur.',
        'email.required' => 'E-posta adresi zorunludur.',
        'email.email' => 'Geçerli bir e-posta adresi giriniz.',
        'tarih.required' => 'Tarih seçimi zorunludur.',
        'tarih.date_format' => 'Tarih formatı hatalı (gün-ay-yıl).',
        'tarih.after_or_equal' => 'Geçmiş bir tarihe randevu alınamaz.',
        'saat.required' => 'Saat seçimi zorunludur.',
        'telefon.required' => 'Telefon numarası zorunludur.',
        'mesaj.required' => 'Mesaj alanı boş bırakılamaz.',
        'soru.required' => 'Soru alanı boş bırakılamaz.',
        'kvkk.required' => 'KVKK metnini onaylamanız gerekmektedir.',
        'cv.required' => 'CV dosyası zorunludur.',
        'cv.mimes' => 'CV sadece PDF, DOC veya DOCX formatında olabilir.',
    ];

    public function __construct(Request $request)
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent() ?? '';
        $service = app(NumberUsersService::class);
        $cihaz = $service->cihazTespiti($userAgent);
        $service->kullaniciKaydet($ip, $cihaz, $userAgent);
    }

    public function iletisimPost(Request $request, AntiSpamService $antiSpamService)
    {
        if ($request->filled('website_url')) return redirect()->back();

        $validator = Validator::make($request->all(), [
            'adSoyad' => 'required|max:250',
            'email' => 'required|email:rfc,dns|max:250',
            'ulkeKodu' => 'required',
            'telefon' => 'required|min:10|max:20',
            'mesaj' => 'required|min:10',
            'kvkk' => 'required',
        ], $this->messages);

        if ($validator->fails()) {
            Alert::error('Hata', implode('<br>', $validator->errors()->all()))->html();
            return redirect()->back()->withInput();
        }

        if (!$this->checkRecaptcha($request->g_recaptcha_response, $request->ip())) {
            Alert::error("Hata", "Güvenlik doğrulaması başarısız.");
            return redirect()->back()->withInput();
        }

        $spamResult = $antiSpamService->inspect($request, 'iletisim');

        $form = new ContactForm();
        $form->fill([
            'formAdi' => ($request->formAdi ?? 'İletişim Formu') . " | Nisanda Adana'da",
            'adSoyad' => $request->adSoyad,
            'email' => $request->email,
            'telefon' => $request->ulkeKodu . ' ' . $request->telefon,
            'konu' => $request->konu ?? 'Genel İletişim',
            'mesaj' => $request->mesaj,
            'izin' => $request->kvkk ? 1 : 0,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => ($spamResult['blocked'] || $spamResult['suspicious']) ? 1 : 0,
            'spam_score' => $spamResult['score'],
            'spam_reasons' => json_encode($spamResult['reasons'] ?? [])
        ]);

        $mailData = [
            "kullaniciAdSoyad" => $request->adSoyad,
            "kullaniciBirim" => "İletişim Formu",
            "kullaniciEmail" => $request->email,
            "kullaniciTelefon" => $form->telefon,
            "kullaniciKonu" => $form->konu ?? 'Genel İletişim',
            "kullaniciMesaj" => $request->mesaj,
            "kullaniciKvkkOnayi" => $request->kvkk ? 'Evet' : 'Hayır', // EKLENDİ
            "kullaniciIP" => $request->ip(),
            "kullaniciTarih" => date('d.m.Y H:i'),
            "subject" => "İletişim Formu | Nisanda Adanada"
        ];

        return $this->processFormSubmission($form, ContactFormMail::class, $mailData, 'İletişim', $spamResult);
    }

    public function insanKaynaklariPost(Request $request, AntiSpamService $antiSpamService)
    {
        if ($request->filled('website_url')) return redirect()->back();

        $validator = Validator::make($request->all(), [
            'adSoyad' => 'required|max:250',
            'email' => 'required|email:rfc,dns',
            'telefon' => 'required',
            'cv' => 'required|mimes:pdf,doc,docx|max:5120',
            'kvkk' => 'required',
        ], $this->messages);

        if ($validator->fails()) {
            Alert::error('İK Hatası', implode('<br>', $validator->errors()->all()))->html();
            return redirect()->back()->withInput();
        }

        $spamResult = $antiSpamService->inspect($request, 'insan_kaynaklari');

        $form = new HumanResourceForm();
        $form->fill([
            'adSoyad' => $request->adSoyad,
            'email' => $request->email,
            'telefon' => $request->ulkeKodu . ' ' . $request->telefon,
            'mesaj' => $request->mesaj,
            'ip_address' => $request->ip(),
            'is_spam' => ($spamResult['blocked'] || $spamResult['suspicious']) ? 1 : 0
        ]);

        if ($request->hasFile('cv')) {
            $cv = $request->file('cv');
            $cvName = Str::slug($request->adSoyad) . '-' . Str::random(10) . '.' . $cv->getClientOriginalExtension();
            $cv->move(public_path('ik-cv/'), $cvName);
            $form->cv = $cvName;
        }

        $mailData = [
            "kullaniciAdSoyad" => $request->adSoyad,
            "kullaniciTelefon" => $form->telefon, // EKLENDİ
            "kullaniciEmail" => $request->email, // EKLENDİ
            "kullaniciKonu" => 'İnsan Kaynakları Başvurusu', // EKLENDİ (Formda ayrı alan varsa $request->konu yapılabilir)
            "kullaniciCvVarMi" => $request->hasFile('cv') ? 'Evet' : 'Hayır', // EKLENDİ
            "kullaniciCvName" => $form->cv ?? 'Belirtilmedi',
            "kullaniciKvkkOnayi" => $request->kvkk ? 'Evet' : 'Hayır', // EKLENDİ
            "kullaniciMesaj" => $request->mesaj ?? 'Belirtilmedi', // EKLENDİ
            "kullaniciTarih" => date('d.m.Y H:i'), // EKLENDİ
            "kullaniciIP" => $request->ip(), // EKLENDİ
            "subject" => "İK Başvurusu | Dr. Lida Çiteli"
        ];

        return $this->processFormSubmission($form, HumanResourceFormMail::class, $mailData, 'İK Formu', $spamResult);
    }

    public function bultenIletisimPost(Request $request, AntiSpamService $antiSpamService)
    {
        // Honeypot Kontrolü
        if ($request->filled('website_url')) return redirect()->back();

        $rules = [
            'adSoyad' => 'required|max:250',
            'email' => 'required|email:rfc,dns|max:250',
            'kvkk' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Alert::error('Hata', implode('<br>', $validator->errors()->all()))->html();
            return redirect()->back()->withInput();
        }

        // reCAPTCHA & AntiSpam Kontrolü
        if (!$this->checkRecaptcha($request->g_recaptcha_response, $request->ip())) {
            Alert::error("Hata", "Güvenlik doğrulaması başarısız.");
            return redirect()->back()->withInput();
        }

        $spamResult = $antiSpamService->inspect($request, 'bulten');
        if ($spamResult['blocked']) {
            Alert::error("Hata", "İşleminiz güvenlik nedeniyle engellendi.");
            return redirect()->back();
        }

        // Model Hazırlığı
        $bultenFormu = new BultenIletisimFormu();
        $bultenFormu->fill([
            'adSoyad' => $request->adSoyad,
            'email' => $request->email,
            'telefon' => $request->ulkeKodu . ' ' . $request->telefon,
            'izin' => $request->kvkk ? 1 : 0,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => $spamResult['suspicious'] ? 1 : 0,
            'spam_score' => $spamResult['score'],
            'spam_reasons' => json_encode($spamResult['reasons'] ?? [])
        ]);

        if ($bultenFormu->save()) {
            // Abone Tablosuna Kayıt
            if ($request->kvkk == 1) {
                BultenIletisimFormu::firstOrCreate(
                    ['email' => $request->email],
                    ['adSoyad' => $request->adSoyad, 'telefon' => $bultenFormu->telefon]
                );
            }

            // Mail Gönderimi
            $ayarlar = Contacts::find(1);
            $companyMail = $ayarlar->email ?? "firmamailformlari@sahnemedya.com";

            $mailData = [
                "subject" => "Bülten Kayıt Formu | " . "Nisanda Adana'da",
                "kullaniciAdSoyad" => $request->adSoyad,
                "kullaniciKonu" => "Bülten Kayıt Formu",
                "kullaniciEmail" => $request->email,
                "kullaniciTelefon" => $bultenFormu->telefon,
                "kullaniciKvkkOnayi" => $request->kvkk ? 'Evet' : 'Hayır',
                "kullaniciIP" => $request->ip(),
                "kullaniciTarih" => now()->format('d.m.Y H:i')
            ];

            Mail::to($companyMail)->send(new BultenIletisimFormuMail($mailData));

            Log::channel('userLog')->info("[BÜLTEN: {$request->email} - IP: {$request->ip()}]");
            Alert::success('Başarılı', 'Bülten kaydınız tamamlandı.');
        }

        return redirect()->back();
    }

    public function yarismaBasvuruPost(Request $request, AntiSpamService $antiSpamService)
    {
        if ($request->filled('website_url')) return redirect()->back();

        $rules = [
            'tur' => 'required|in:vitrin,balkon',
            'adSoyad' => 'required|max:250',
            'email' => 'required|email:rfc,dns|max:250',
            'fotograflar' => 'required|array|min:1|max:5',
            'fotograflar.*' => 'image|mimes:jpeg,png,jpg|max:20480',
            'kvkk' => 'required',
        ];

        if ($request->tur == 'vitrin') $rules['isletme_adi'] = 'required|max:250';
        if (!$request->has('resit_mi')) $rules['veli_izin_belgesi'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:10240';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        // AntiSpam
        $spamResult = $antiSpamService->inspect($request, 'yarisma');
        if ($spamResult['blocked']) return redirect()->back()->with('error', 'Güvenlik engeli.');

        // Dosya Yükleme İşlemleri
        $yuklenenResimler = [];
        if ($request->hasFile('fotograflar')) {
            foreach ($request->file('fotograflar') as $file) {
                $fileName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/yarisma/suslemeler'), $fileName);
                $yuklenenResimler[] = 'images/yarisma/suslemeler/' . $fileName;
            }
        }

        $veliBelgeYolu = null;
        if ($request->hasFile('veli_izin_belgesi')) {
            $file = $request->file('veli_izin_belgesi');
            $fileName = time() . '-veli-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/yarisma/belgeler'), $fileName);
            $veliBelgeYolu = 'images/yarisma/belgeler/' . $fileName;
        }

        $yarisma = new BalkonVitrinBasvuru();
        $yarisma->fill([
            'tur' => $request->tur,
            'isletme_adi' => $request->isletme_adi,
            'ad_soyad' => $request->adSoyad,
            'email' => $request->email,
            'telefon' => $request->ulkeKodu . ' ' . $request->telefon,
            'adres' => $request->adres,
            'resit_mi' => $request->has('resit_mi') ? 1 : 0,
            'veli_izin_belgesi' => $veliBelgeYolu,
            'fotograflar' => $yuklenenResimler,
            'kvkk' => $request->kvkk ? 1 : 0,
            'ip_adresi' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => $spamResult['suspicious'] ? 1 : 0,
            'spam_score' => $spamResult['score'],
            'spam_reasons' => json_encode($spamResult['reasons'] ?? [])
        ]);

        if ($yarisma->save()) {
            $ayarlar = Contacts::find(1);
            $alicilar = [$ayarlar->email, "firmamailformlari@sahnemedya.com", "susleme@nisandaadanada.com"];

            $mailData = [
                "subject" => "Yeni Yarışma Başvurusu: " . strtoupper($request->tur),
                "tur" => $request->tur,
                "kullaniciAdSoyad" => $request->adSoyad,
                "isletme_adi" => $request->isletme_adi,
                "kullaniciEmail" => $request->email,
                "kullaniciTelefon" => $yarisma->telefon,
                "fotograflar" => $yuklenenResimler,
                "veli_belgesi" => $veliBelgeYolu,
                "kullaniciIP" => $request->ip()
            ];

            Mail::to($alicilar)->send(new BalkonVitrinYarismaMail($mailData));
            Alert::success('Başarılı', 'Başvurunuz alındı.');
        }

        return redirect()->back();
    }

    public function portakalliLezzetlerPost(Request $request, AntiSpamService $antiSpamService)
    {
        if ($request->filled('website_url')) return redirect()->back();

        $rules = [
            'adsoyad' => 'required|max:250',
            'tc' => 'required|digits:11',
            'dogumtarihi' => 'required',
            'telefon' => 'required',
            'mail' => 'required|email',
            'yemekAdi' => 'required|max:250',
            'yemekMalzemesi' => 'required',
            'yemekTarifi' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Alert::error('Hata', 'Lütfen tüm alanları doğru doldurun.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // AntiSpam & reCAPTCHA
        $spamResult = $antiSpamService->inspect($request, 'lezzetler');
        if ($spamResult['blocked'] || !$this->checkRecaptcha($request->g_recaptcha_response, $request->ip())) {
            Alert::error("Hata", "Güvenlik doğrulaması başarısız.");
            return redirect()->back();
        }

        $lezzetData = new PortakalliLezzetler();
        $lezzetData->fill([
            'ad' => $request->adsoyad,
            'kategori' => $request->kategori,
            'tc' => $request->tc,
            'dogum' => $request->dogumtarihi,
            'adres' => $request->adres,
            'telefon' => $request->ulkeKodu . ' ' . $request->telefon,
            'mail' => $request->mail,
            'meslek' => $request->meslek,
            'yemekadi' => $request->yemekAdi,
            'yemekmalzemesi' => $request->yemekMalzemesi,
            'yemektarifi' => $request->yemekTarifi,
            'bilgi' => date('d.m.Y') . " - " . $request->ip(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_spam' => $spamResult['suspicious'] ? 1 : 0,
            'spam_score' => $spamResult['score'],
            'spam_reasons' => json_encode($spamResult['reasons'] ?? [])
        ]);

        if ($lezzetData->save()) {
            $companyMail = "nevra.pehlivan@parkzirve.com.tr";

            $mailData = [
                "subject" => "Portakallı Lezzetler Başvurusu | " . config('app.name'),
                'ad' => $request->adsoyad,
                'kategori' => $request->kategori,
                'tc' => $request->tc,
                'dogum' => $request->dogumtarihi,
                'adres' => $request->adres,
                'telefon' => $request->ulkeKodu . ' ' . $request->telefon,
                'mail' => $request->mail,
                'meslek' => $request->meslek,
                'yemekadi' => $request->yemekAdi,
                'yemekmalzemesi' => $request->yemekMalzemesi,
                'yemektarifi' => $request->yemekTarifi,
                'kvkk' => $request->kvkk ? 'Onaylandı' : 'Onaylanmadı',
                'sartname' => $request->sartname ? 'Onaylandı' : 'Onaylanmadı',
                "kullaniciIP" => $request->ip(),
                "kullaniciTarih" => date('d.m.Y')
            ];

            Mail::to([$companyMail, "firmamailformlari@sahnemedya.com"])->send(new portakalliLezzetlerFormuMail($mailData));
            Alert::success("Başvurunuz Alındı", "Bilgileriniz başarıyla kaydedildi.");
        }

        return redirect()->back();
    }


    public function index()
    {
        $activeLangId = Session::get('uLangId') ?? 1;
        $siteSetting = SiteSettings::first();

        // ----------------------------------------------------------
        // DİLE GÖRE KATEGORİ ID'LERİNİ ÇÖZ
        // Türkçe'deki orijinal ID'ler referans alınıyor.
        // Aktif dil Türkçe ise aynı ID'ler, değilse çevirilerinin ID'leri kullanılır.
        // ----------------------------------------------------------
        $turkceIzinliKategoriIdler = [1, 5];
        $turkceAdanaKategoriId = 4;
        $turkceHaricKategoriIdler = [4]; // 8 zaten 4'ün İngilizce çevirisi

        // İzinli kategorilerin aktif dildeki ID'leri
        $izinliKategoriIdler = Category::where('lang_id', $activeLangId)
            ->where(function ($q) use ($turkceIzinliKategoriIdler) {
                $q->whereIn('id', $turkceIzinliKategoriIdler)               // Türkçe orijinal
                ->orWhereIn('translation_of', $turkceIzinliKategoriIdler); // Diğer dil çevirileri
            })
            ->pluck('id')
            ->toArray();

        // Hariç kategorilerin aktif dildeki ID'leri
        $haricKategoriIdler = Category::where('lang_id', $activeLangId)
            ->where(function ($q) use ($turkceHaricKategoriIdler) {
                $q->whereIn('id', $turkceHaricKategoriIdler)
                    ->orWhereIn('translation_of', $turkceHaricKategoriIdler);
            })
            ->pluck('id')
            ->toArray();

        // Adana kategorisinin aktif dildeki ID'leri
        $adanaKategoriIdler = Category::where('lang_id', $activeLangId)
            ->where(function ($q) use ($turkceAdanaKategoriId) {
                $q->where('id', $turkceAdanaKategoriId)
                    ->orWhere('translation_of', $turkceAdanaKategoriId);
            })
            ->pluck('id')
            ->toArray();

        $izinliBladeIdler = [9, 13, 1, 17];

        // ----------------------------------------------------------
        // DUYURULAR (değişiklik yok, blade_id dile göre değişmiyor)
        // ----------------------------------------------------------
        $duyurular = Page::where('blade_id', 33)
            ->where('published', 1)
            ->where('lang_id', $activeLangId)
            ->whereNotIn('title', function ($query) {
                $query->select('name')
                    ->from('categories')
                    ->whereColumn('categories.id', 'pages.category_id');
            })
            ->orderBy('id', 'desc')
            ->get();

        // EKLENDİ: Az slayt varsa loop için çoğalt
        if ($duyurular->count() > 0 && $duyurular->count() <= 5) {
            $duyurular = $duyurular->concat($duyurular);
        }

        // ----------------------------------------------------------
        // KARNAVAL BİLGİLERİ
        // ----------------------------------------------------------
        $karnavalBilgiler = Page::where('show_homepage', 1)
            ->where('lang_id', $activeLangId)
            ->whereIn('category_id', $izinliKategoriIdler)   // Artık dinamik
            ->whereIn('blade_id', $izinliBladeIdler)
            ->whereNotIn('category_id', $haricKategoriIdler) // Artık dinamik
            ->whereDoesntHave('parentCategory', function ($query) use ($haricKategoriIdler) {
                $query->whereIn('id', $haricKategoriIdler);
            })
            ->orderBy('id', 'asc')
            ->get();

        if ($karnavalBilgiler->count() > 0 && $karnavalBilgiler->count() <= 5) {
            $karnavalBilgiler = $karnavalBilgiler->concat($karnavalBilgiler);
        }

        // ----------------------------------------------------------
        // ADANADA (artık dile göre doğru kategori ID'lerini kullanıyor)
        // ----------------------------------------------------------
        $adanada = Page::whereIn('category_id', $adanaKategoriIdler)
            ->where('show_homepage', 1)
            ->where('lang_id', $activeLangId)
            ->limit(6)
            ->get();

        // ----------------------------------------------------------
        // İZLENİMLER, SLIDER, ŞEMA (değişiklik yok)
        // ----------------------------------------------------------
        $izlenimler = Comments::where('lang_id', $activeLangId)
            ->orderBy("hit", "asc")
            ->limit(10)
            ->get();
        $izlenimlerCount = $izlenimler->count();

        $sliders = Slider::where('published', 1)
            ->where('lang_id', $activeLangId)
            ->orderBy('hit', 'desc')
            ->get();

        $contact = Contacts::with('socialMedia')->first();
        $graph = [
            PageSchemasService::getWebSiteSchema($siteSetting, $activeLangId),
            PageSchemasService::getOrganizationSchema($siteSetting, $contact, $activeLangId)
        ];
        $schemaScriptsHtml = PageSchemasService::render($graph);

        // Geçici debug - dil değiştirip her iki dilde de bak
//        dd([
//            'activeLangId' => $activeLangId,
//            'izinliKategoriIdler' => $izinliKategoriIdler,
//            'haricKategoriIdler' => $haricKategoriIdler,
//            'adanaKategoriIdler' => $adanaKategoriIdler,
//        ]);

        return view('user.index', compact(
            'sliders', 'duyurular', 'karnavalBilgiler', 'adanada',
            'izlenimler', 'izlenimlerCount', 'schemaScriptsHtml'
        ));
    }


    public function slug($slug)
    {
        $activeLangId = Session::get('uLangId') ?? 1;
        // Slug'a göre sayfayı bul ve blade bilgisini de getir
        $page = Page::with(['blade', 'pageSchema', 'seo'])->where('slug', $slug)->firstOrFail();
        $siteSetting = SiteSettings::first();
        // Ana sayfa bilgisini koru (breadcrumb için)
        $mainPage = $page;
        // Bu sayfaya ait galeri resimlerini getir (ID'ye göre sıralı)
        $galleries = $page->effectiveGalleries()->map(function ($gallery) {
            $gallery->image = $gallery->image(); // resim URL'ini direkt JS'e hazırla
            return $gallery;
        });

        // $galerisayfa için de aynı mantık (blade'de count vs için kullanılıyor olabilir)
        $galleryPageId = ($galleries->isNotEmpty() && $page->translation_of && Gallery::where('page_id', $page->id)->doesntExist())
            ? $page->translation_of
            : $page->id;

        $galerisayfa = Gallery::where('page_id', $galleryPageId);


        // Blade dosyasının yolunu al ve .blade.php uzantısını kaldır
        if ($page->blade) {
            // normal.blade.php -> normal
            $fileName = str_replace('.blade.php', '', $page->blade->file);
            $bladeFile = 'user.blades.' . $fileName;
        } else {
            $bladeFile = 'user.blades.default';
        }
        $sss = FAQ::where('page_id', $page->id)->where('lang_id', $activeLangId)->orderBy('hit', 'asc')->get();
        $contacts = Contacts::all();
        $galleryAll = Gallery::all();
        $sertifikalar = Certificate::where('published', 1)->get();
        $galleryOverview = Gallery::with('page')->get();
        $randevuHizmet = Page::where('category_id', 3)->get();

        $relatedNews = $this->getRelatedNews($page);

        $effectiveSezonId = $page->id;
        if ($page->translation_of) {
            $hasOwnReferences = References::where('sezon_id', $page->id)->exists();
            if (!$hasOwnReferences) {
                $effectiveSezonId = $page->translation_of;
            }
        }

        $sponsorsQuery = $page->effectiveReferences()
            ->with(['type.translations', 'karnavalSezonus'])
            ->join('reference_types', 'references.type_id', '=', 'reference_types.id')
            ->join('karnaval_sezonus', 'references.sezon_id', '=', 'karnaval_sezonus.id')
            ->orderBy('karnaval_sezonus.karnaval_yili', 'desc')  // en yeni yıl üstte
            ->orderBy('reference_types.hit', 'asc')
            ->orderBy('references.hit', 'asc')
            ->select('references.*');

        $sponsorlar = $sponsorsQuery->get()
            // Önce yıla, sonra tür adına göre nested gruplama
            ->groupBy(fn($ref) => $ref->karnavalSezonus?->karnaval_yili ?? 'Diğer')
            ->map(function ($refsInYear) use ($activeLangId) {
                return $refsInYear->groupBy(
                    fn($ref) => $ref->type?->localizedName($activeLangId) ?? 'Diğer'
                );
            });
        //
        //        $relatedDuyurular = $this->getRelatedDuyurular($page);

        // --- ŞEMA BÖLÜMÜ (OPTIMIZE EDILDI) ---
        $contactObj = Contacts::with('socialMedia')->first();

        // 1. Global verileri bir diziye alıyoruz
        $graph = [
            PageSchemasService::getWebSiteSchema($siteSetting, $activeLangId),
            PageSchemasService::getOrganizationSchema($siteSetting, $contactObj, $activeLangId)
        ];

        // 2. Sayfaya özel veriyi generate ediyoruz (SSS ve Video içinden geliyor)
        // Bu metod render() döndürdüğü için içindeki JSON'u ayıklayıp ana graph'a eklemek en temizi
        // Ancak yapıyı bozmamak için senin kullandığın yöntemle birleştiriyorum:
        $schemaScriptsHtml = PageSchemasService::render($graph);


        if ($page->pageSchema && $page->pageSchema->is_active) {
            $schemaScriptsHtml = PageSchemasService::generate($page, 'aesthetic');
        }


        // Sayfa verilerini view'e gönder (galleries eklendi)
        return view($bladeFile, compact('page', 'galleries', 'galerisayfa', 'relatedNews', 'sertifikalar', 'contacts', 'randevuHizmet', 'galleryAll', 'galleryOverview', 'sss', 'schemaScriptsHtml', 'sponsorlar'));
    }

    private function getRelatedNews($currentPage, $limit = 3)
    {
        // Eğer sayfa category_id'si yoksa boş collection döndür
        if (!$currentPage->category_id) {
            return collect();
        }

        // Kategorinin "blog" sayfasını bul (eğer varsa)
        $categoryBlogPage = Page::where('category_id', $currentPage->category_id)
            ->where('slug', 'blog')
            ->first();

        // Hariç tutulacak ID'leri belirle
        $excludeIds = [$currentPage->id];
        if ($categoryBlogPage) {
            $excludeIds[] = $categoryBlogPage->id;
        }

        // Mevcut haberin tarihinden önce yayınlanmış haberler (aynı kategoriden)
        $previousNews = Page::where('category_id', $currentPage->category_id)
            ->where('created_at', '<', $currentPage->created_at)
            ->whereNotIn('id', $excludeIds)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        // Eğer yeterli haber yoksa, sonra yayınlananlardan tamamla (yine aynı kategoriden)
        if ($previousNews->count() < $limit) {
            $needed = $limit - $previousNews->count();

            $nextNews = Page::where('category_id', $currentPage->category_id)
                ->where('created_at', '>', $currentPage->created_at)
                ->whereNotIn('id', $excludeIds)
                ->orderBy('created_at', 'asc')
                ->take($needed)
                ->get();

            // Önceki ve sonraki haberleri birleştir
            $relatedNews = $previousNews->merge($nextNews);
        } else {
            $relatedNews = $previousNews;
        }

        return $relatedNews;
    }


    public function sitemap()
    {
        $slugs = Page::select('slug', 'updated_at')->where('id', '>', 0)->get(); // model adınızı yazın

        return response()->view('user.sitemap', compact('slugs'))
            ->header('Content-Type', 'application/xml');
    }

    public function aramaKayit(AramaKayitRequest $request)
    {
        try {
            // Javascript'ten 'arama' geliyor, IP'yi buradan ekliyorsun. Doğru.
            $data = array_merge($request->validated(), ['ip' => $request->ip()]);

            app(AramaKayitService::class)->kayitEkle($data);

            return response()->json(['success' => 'Arama kaydı eklendi']);
        } catch (\Exception $e) {
            Log::error('Controller hata:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Kayıt yapılamadı'], 500);
        }
    }
}
