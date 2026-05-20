# GaziCoskunPanel — Mimari Rehberi

> Bu doküman GaziCoskunPanel projesinin yapısını anlatır. Yeni Laravel CMS projelerinde bu projeden kopyalanan dosyaları doğru bir biçimde değerlendirmek ve aynı pattern'leri sürdürmek için referans olarak kullanılır.

---

## 1. Genel Bakış

- **Tür:** Laravel tabanlı kurumsal web sitesi + CMS yönetim paneli (iki yüzlü uygulama)
- **Kullanıcı tipleri:** Ziyaretçi (user), panel yöneticisi (cms)
- **Dil:** Türkçe varsayılan, çoklu dil desteği (Language modeli + `translation_of` ilişkisi)

## 2. Tech Stack

| Katman | Teknoloji |
|---|---|
| Backend | PHP 8.3+, Laravel 12 |
| DB | MySQL 8+ |
| Asset pipeline | Vite + SCSS (Dart Sass) |
| Frontend lib | Swiper 11 (carousel), Fancybox v4 (lightbox), Flatpickr (datepicker), SweetAlert2, Alpine.js, jQuery (kalan minimal kullanım, **yeni kodda kullanma**) |
| İkon | Line Awesome 1.3, Font Awesome 7 |
| Editör | CKEditor (sayfa içeriği için) |

**Önemli:** Laravel 12 kullandığı için `app/Http/Kernel.php` **yok** — middleware yapılandırması `bootstrap/app.php`'de yapılır.

---

## 3. Klasör Yapısı (Yüksek seviye)

```
GaziCoskunPanel/
├── app/
│   ├── Console/Commands/        # Artisan komutları (cron işleri)
│   ├── Http/
│   │   ├── Controllers/Cms/     # Panel controller'ları
│   │   ├── Controllers/User/    # Ziyaretçi controller'ları
│   │   └── Middleware/          # Custom middleware (auth, geo, security)
│   ├── Mail/                    # Mailable sınıfları
│   ├── Models/                  # Eloquent modeller
│   ├── Observers/               # Model observer'lar
│   ├── Providers/               # Servis sağlayıcıları
│   ├── Services/                # İş mantığı (Controller → Service → Model)
│   └── Traits/                  # Paylaşılan trait'ler
├── bootstrap/app.php            # Middleware + routing config (Laravel 12)
├── config/                      # services.php, app.php vb.
├── database/migrations/         # Tablo şemaları
├── resources/
│   ├── css/
│   │   ├── app.scss             # Panel (CMS) CSS — Vite girdisi
│   │   └── style.scss           # Ziyaretçi (user) CSS — Vite girdisi
│   ├── js/
│   │   ├── app.js               # Panel JS — jQuery + sidebar
│   │   ├── style.js             # Ziyaretçi JS — Vite girdisi (Swiper, Fancybox, Flatpickr init'leri)
│   │   └── user/script.js       # ⚠ ESKİ jQuery kalıntısı — public/js/user/script.js'in kaynağı, dokunma
│   ├── views/
│   │   ├── cms/                 # Panel blade'leri (modüllere göre alt klasör)
│   │   ├── user/                # Ziyaretçi blade'leri
│   │   └── errors/              # 401/403/404/419/500/503 hata sayfaları
│   └── lang/                    # Çeviri dosyaları
├── routes/
│   ├── web.php                  # Ana route giriş (cms + user dahil edilir)
│   ├── auth.php
│   ├── noAuth.php
│   ├── cms/                     # Panel route'ları (modüle göre ayrı dosya)
│   └── user/                    # Ziyaretçi route'ları
├── vite.config.js
└── package.json
```

---

## 4. Backend Mimarisi

### 4.1 Service katmanı (önemli pattern)

**Tüm iş mantığı `app/Services/` altında bir Service sınıfında.** Controller'lar yalnızca request alır, Service'i çağırır, response döner. Tipik akış:

```
Request → Controller → Service → Model → DB
                          ↓
                   array dönüşü
                          ↓
                  Controller → JSON/View
```

**Service dönüş konvansiyonu:** Service metodları array döner:
```php
return [
    "status"  => "success",       // veya "error"
    "message" => "İşlem başarılı",
    "data"    => $payload,         // opsiyonel
];
```

Controller bu array'i kullanıp `back()->with(...)` veya `response()->json(...)` döner.

### 4.2 Mevcut Service'ler (alfabetik özet)

| Service | Sorumluluk |
|---|---|
| `ApiKeyService` | API anahtarları/site ayarları (panel'den yönetilen Google/YouTube/reCAPTCHA) |
| `AntiSpamService` | Form spam koruması |
| `AramaKayitService` | Site içi arama loglama |
| `BladeService` | Sayfa şablonları (Blade) yönetimi |
| `CategoryService` | Kategori CRUD |
| `CertificateService` | Sertifika yönetimi |
| `CommonService` | Resim upload, WebP dönüşüm, dosya silme yardımcıları |
| `ContactsService` | İletişim bilgileri |
| `ContentQualityService` | Sayfa içerik kalitesi puanlama |
| `FAQServices` | Sık sorulan sorular |
| `GalleryService` | Galeri görselleri |
| `GeminiService` | Google Gemini AI entegrasyonu (içerik üretimi) |
| `GeoService` | LLMS.txt üretimi + GEO (Generative Engine Optimization) |
| `GoogleReviewService` | Google Places API yorumlar |
| `LanguageService` | Dil yönetimi |
| `LeadScoringService` | Form lead puanlama |
| `LogService` | Özel log kanalı (DB-based veya log dosyası) |
| `MediaProcessorService` | Toplu medya işleme |
| `MonthlyReportService` | Aylık rapor üretimi |
| `NumberUsersService` | İstatistik/sayaç |
| `PageSchemasService` | Schema.org JSON-LD yönetimi |
| `PageService` | Ana sayfa CRUD (Page modeli — site içeriğinin omurgası) |
| `PageTemplateService` | Sayfa şablonu yönetimi |
| `PagespeedService` | Google PageSpeed Insights API |
| `PanelMenuSettingService` | Panel side menu özelleştirme |
| `PartnersSolutionService` | Çözüm ortakları |
| `PermissionService` | Yetki yönetimi |
| `PopupService` | Popup yönetimi |
| `PressKitService` | Basın kiti |
| `ReferenceService` | Referanslar |
| `RolePermissionService` | Rol-yetki ilişkisi |
| `RoleUserService` | Kullanıcı-rol ilişkisi |
| `RolesService` | Rol CRUD |
| `SeoAuditService` | SEO denetimi (Page bazlı sorun tespiti) |
| `SeoService` | SEO meta CRUD (Seo modeli) |
| `SideMenuElementService` | Side menu öğeleri + sayfa çevirileri |
| `SiteHealthService` | Site sağlığı kontrolleri (SSL, header, uptime, 404, mail) |
| `SiteSettingsService` | Genel site ayarları |
| `SliderService` | Anasayfa slider yönetimi |
| `SocialMediaService` | Sosyal medya linkleri |
| `YoutubeService` | YouTube API'den video senkronizasyonu |

### 4.3 Modeller

Önemli model ilişkileri:
- `Page` (sayfa) ← omurga model. Bağımlılıklar:
  - `hasOne(Seo::class, 'page_id')` — SEO meta + GEO alanları (`geo_title`, `geo_description`)
  - `belongsTo(Category::class)` — kategori
  - `belongsTo(Language::class, 'lang_id')` — dil
  - `belongsTo(Page::class, 'parent_page')` — üst sayfa (hiyerarşi)
  - `belongsTo(Page::class, 'translation_of')` — çeviri kaynak sayfası
  - `hasMany(Page::class, 'parent_page')` — alt sayfalar
  - `hasMany(Page::class, 'translation_of')` — diğer dil çevirileri
  - `belongsTo(Blade::class, 'blade_id')` — sayfa şablonu
  - `hasOne(PageSchemas::class, 'page_id')` — schema.org JSON-LD
  - `hasMany(FAQ::class)`, `hasMany(Gallery::class)`
- `Seo` — Page'e bağlı, `geo_title`/`geo_description` da burada
- `ApiKeys` — panel'den yönetilen ayarlar (Google Place ID, YouTube Channel ID, vb.)

### 4.4 Controllers (Cms/ altında)

Modüle göre ayrılmış. Her Controller bir `*Service` enjekte eder constructor'da:

```php
class YoutubeVideosController extends Controller
{
    protected YoutubeService $youtubeService;

    public function __construct(YoutubeService $youtubeService)
    {
        $this->youtubeService = $youtubeService;
    }
    // ...
}
```

### 4.5 Middleware

| Middleware | Görev |
|---|---|
| `Authenticate` | Login kontrolü |
| `LoginPageAuthControl` | Zaten giriş yapmışsa login sayfasına gitme |
| `CheckPermission` | `permission:slug` alias'ı — yetki kontrolü |
| `GeoMiddleware` | IP'den ülke tespiti (req'e ekler) |
| `LanguageMiddleware` | Aktif dili `App::setLocale()` ile ayarlar |
| `LogPageProps` | Sayfa görüntülenme logu |
| `SecurityHeaders` | HSTS, X-Frame-Options, X-Content-Type-Options, CSP, Referrer-Policy — web grubunda otomatik |

**`bootstrap/app.php` middleware kayıt yeri:**
```php
$middleware->web(append: [
    \App\Http\Middleware\GeoMiddleware::class,
    \App\Http\Middleware\LanguageMiddleware::class,
    \App\Http\Middleware\LogPageProps::class,
    \App\Http\Middleware\SecurityHeaders::class,
]);

$middleware->alias([
    'auth' => Authenticate::class,
    'loginPageAuthControl' => LoginPageAuthControl::class,
    'permission' => CheckPermission::class,
    'geo' => GeoMiddleware::class,
]);
```

### 4.6 Route yapısı

**Modüler.** `routes/web.php` ana giriş; `routes/cms/*.php` ve `routes/user/*.php` dosyaları include edilir.

`routes/cms/` altındaki her modül kendi dosyasında (ör. `routes/cms/videos.php`, `routes/cms/seoAudit.php`).

Route naming: `cms.{module}.{action}` (örn: `cms.videos.sync`, `cms.seo-audit.run`).

---

## 5. Frontend Mimarisi

### 5.1 İki ayrı sayfa dili

| Taraf | View klasörü | CSS | JS |
|---|---|---|---|
| **Panel (CMS)** | `resources/views/cms/` | `resources/css/app.scss` | `resources/js/app.js` (jQuery + DataTables + Sidebar) |
| **Ziyaretçi (User)** | `resources/views/user/` | `resources/css/style.scss` | `resources/js/style.js` |

### 5.2 `resources/js/style.js` (ziyaretçi JS) — TÜM SWİPER/FANCYBOX/FLATPİCKR İNİT'LERİ BURADA

Vite ile derlenir. Footer'da `@vite(['resources/js/style.js'])` ile yüklenir.

Mevcut yapı:
1. **Imports** (en üstte):
   ```js
   import flatpickr from 'flatpickr';
   import { Turkish } from 'flatpickr/dist/l10n/tr.js';
   import 'flatpickr/dist/flatpickr.min.css';
   flatpickr.localize(Turkish);
   ```
2. **Vanilla JS mobile menu controller** (IIFE) — `mmenu` plugin'i kaldırıldı, vanilla replaced.
3. **DOMContentLoaded içinde Swiper/Fancybox init'leri:**
   - `Fancybox.bind('[data-fancybox]')` — global
   - `.referanslarimiz-swiper` — autoplay, loop
   - `.hizmetlerimiz-swiper` — desktop 3 / tablet 2 / mobil 1, loop koşullu (slide sayısı yeterli ise)
   - `.anasayfa-slider-swiper` — hero (Owl'dan Swiper'a geçirildi)
   - `#datepickerx` — Flatpickr (jQuery UI'dan geçirildi)

**Yeni Swiper/Fancybox/modal init ekleyeceksen yeri burası.** `public/js/user/script.js`'e ekleme — orası eski jQuery kalıntı.

### 5.3 `public/js/user/script.js` — ESKİ KALINTI ⚠

`<script src="{{asset('js/user/script.js')}}">` ile yüklenir (Vite dışı). İçinde vanilla `window.addEventListener('scroll', ...)` sticky header var. Dokunma; yeni kod yazma — `resources/js/style.js`'i kullan.

### 5.4 Vite config

```js
// vite.config.js
input: [
    'resources/css/app.scss',
    'resources/js/app.js',
    'resources/css/style.scss',
    'resources/js/style.js'
],
server: {
    host: '127.0.0.1',           // IPv4 zorlanır (CSP IPv6 [::1] syntax'ını desteklemiyor)
    hmr: { host: '127.0.0.1' },
}
```

### 5.5 Asset yüklenme noktaları (user)

| Asset | Nerede |
|---|---|
| Vite (style.scss + style.js) | `resources/views/user/partial/footer.blade.php` → `@vite(['resources/js/style.js'])` |
| Swiper CDN | footer.blade.php (CDN ile) |
| Fancybox v4 CDN | footer.blade.php (CDN ile) |
| Line Awesome / Font Awesome | head.blade.php |
| Owl Carousel | ❌ KALDIRILDI |
| jQuery, jQuery UI | ❌ KALDIRILDI |

---

## 6. Önemli Pattern'ler

### 6.1 SEO + GEO

`pages` tablosu site içeriğin omurgası. SEO meta + GEO (Generative Engine Optimization, yapay zekâ optimizasyonu) için ayrı `seos` tablosu:

```sql
seos (
  id, page_id (FK), title, description,
  geo_title, geo_description,        -- GEO/AEO için
  canonical (FK, default = page_id), -- self-canonical
  timestamps
)
```

Form'larda standart SEO bloku (create/edit blade'lerinde):
- `seo_title`, `geo_title` (input)
- `seo_description`, `geo_description` (textarea)
- Canonical genelde gerek yok (Service `saveSeo()` otomatik self-canonical atar — boş gelirse `$pageId` kullanır)

**Page modelinde `withGeo` scope'u:** `Page::withGeo()` → `with('seo')` eager-load için kullanılır.

### 6.2 API Key Yönetimi

**Yapı:** `api_keys` tablosu — panel'den yönetilen tek satırlı ayar tablosu (key='main' tek kayıt). **JSON yerine her ayar ayrı kolon** (NupelInsaat pattern'ine geçildi, GaziCoskun'da hâlâ JSON `settings` olabilir):

```php
// Yeni pattern (önerilen)
$table->string('youtube_channel_id')->nullable();
$table->string('google_place_id')->nullable();
$table->string('google_places_api_key')->nullable();
$table->string('pagespeed_api_key')->nullable();
$table->string('google_analytics_id')->nullable();
$table->string('clarity_project_id')->nullable();
$table->string('recaptcha_site_key')->nullable();
$table->string('recaptcha_secret_key')->nullable();
```

**Hangi key panel'de, hangisi .env'de:**
- **Panel'de (DB):** `youtube_channel_id`, `google_place_id`, `google_places_api_key`, `pagespeed_api_key`, `clarity_project_id`, `google_analytics_id`, `recaptcha_*`
- **`.env`'de:** `YOUTUBE_API_KEY`, `GEMINI_API_KEY` (henüz panel'e taşınmadı), DB credentials, MAIL_*

**Service'ten erişim:**
```php
$apiKey = ApiKeys::first()?->pagespeed_api_key;       // DB'den
$ytKey  = config('services.youtube.key');             // .env'den (config/services.php aracılığıyla)
```

⚠ `config('services.xxx.key')` çağırıyorsan `config/services.php`'de **mutlaka** o servisi tanımla:
```php
'youtube' => ['key' => env('YOUTUBE_API_KEY')],
```
Aksi takdirde sessizce null döner ve "log'da hata yok ama API çalışmıyor" durumu yaşanır.

### 6.3 Sayfa Şablonu (Blade) Sistemi

`blades` tablosu — admin'in seçebileceği şablon listesi. Her Page bir `blade_id` ile bir şablona bağlı. Şablon dosyaları `resources/views/user/blades/` altında. Page render edilirken kendi blade'i extends edilir.

### 6.4 Çoklu Dil Çevirileri

`pages.translation_of` self-foreign key. Ana dil sayfası (örn. Türkçe) `translation_of = NULL`. Çevirileri `translation_of = ana_page_id`.

Çeviri sayfası oluşturma: `SideMenuElementController::createLanguage()` + `SideMenuElementService::createLanguage()` → mevcut bir sayfanın çevirisini oluşturur (kategori/üst sayfa/blade kaynaktan kopyalanır, içerik boş başlar).

### 6.5 GEO (LLMS.txt) Üretimi

`GeoService` Google'ın AI tarama protokolü için `llms.txt` üretir. `Page::withGeo()` scope'unu kullanır (SEO ile birlikte eager load).

### 6.6 Site Sağlığı

`SiteHealthService::runAll()` 7 kontrol yapar:
- SSL (parse_url config('app.url')'den scheme okur — APP_URL https:// olmalı)
- Security Headers (kendi sitesine self-request — SecurityHeaders middleware'i ile gelen header'ları çeker)
- Uptime (cron'la beslenen `site_health_pings` tablosu)
- robots.txt, sitemap.xml (HEAD request)
- 404 logları (`site_404_logs`)
- Mail config (`config('mail.default') != 'log'` mı)

Sonuç `site_health_snapshots` tablosuna kaydedilir, panel'de gösterilir.

### 6.7 Form yapısı — standart pattern

CMS create/edit blade'lerinde tipik yapı:

```blade
<form action="{{ route('cms.module.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger">...</div>
    @endif

    <div class="grid-form">
        <div class="form-group">
            <label for="title"><i class="las la-heading"></i> Başlık</label>
            <input type="text" name="title" value="{{ old('title') }}">
        </div>
        {{-- ... --}}

        {{-- SEO/GEO bloğu (eğer Page bağlıysa) --}}
    </div>

    <div class="form-actions">
        <a href="..." class="btn-cancel">İPTAL</a>
        <button type="submit" class="btn-save">KAYDET</button>
    </div>
</form>
```

---

## 7. Güvenlik

### 7.1 SecurityHeaders middleware (`app/Http/Middleware/SecurityHeaders.php`)

- HSTS (sadece `$request->isSecure()` ise)
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- Referrer-Policy: strict-origin-when-cross-origin
- Content-Security-Policy: proje CDN'leri + Vite dev loopback (`http://localhost:*`, `http://127.0.0.1:*`) whitelist

**CSP genişletme:** Yeni bir CDN/inline pattern eklenince `SecurityHeaders.php`'deki ilgili directive'e ekle (script-src, style-src, connect-src, frame-src).

### 7.2 Permission sistemi

`role_users` → kullanıcı-rol; `role_permissions` → rol-yetki. Route'larda middleware:
```php
Route::middleware(['auth', 'permission:videos.manage'])->group(function () { ... });
```

---

## 8. Konvansiyonlar

### 8.1 Dil

- **Kod:** İngilizce (sınıf/değişken/method adları)
- **Mesajlar:** Türkçe (LogService::add, flash mesajlar, exception text, blade)
- **Yorum:** Türkçe veya İngilizce — proje genelinde karışık ama Türkçe çoğunlukta

### 8.2 Service dönüş

```php
return ["status" => "success", "message" => "..."];
return ["status" => "error",   "message" => "..."];
```

### 8.3 LogService kullanımı

```php
LogService::add("Module Action", "success", "Mesaj");
LogService::add("Module Action Error", "error", "Hata: " . $exception->getMessage());
```

### 8.4 Route adlandırma

`cms.{module}.{action}` — örn:
- `cms.videos.index`, `cms.videos.sync`, `cms.videos.toggle-status`
- `cms.seo-audit.run`, `cms.seo-audit.resolve`

### 8.5 Migration adlandırma

`YYYY_MM_DD_HHMMSS_action_table.php` — Laravel default.

### 8.6 View klasörü adı

`snake_case_klasör` (örn. `seo_audit`, `google_reviews`, `site_health`).

---

## 9. Sık Karşılaşılan Tuzaklar

1. **Brand renkler:** Bu projede kırmızı (`#cb0b00`, `#da291c`) + mavi (`#5177aa`, `#007bc4`, `#04577e`) kullanılır. **Altın/sarı (`#aa8128` vs.) YASAK** — eski şablon kalıntısı. Yeni component'lerde kullanma.

2. **Owl Carousel & jQuery:** Projeden kaldırıldı. Yeni slider için Swiper kullan (`resources/js/style.js`'e init ekle). jQuery sözdiziminden kaçın.

3. **`config()` vs `env()`:** Kod içinde **asla** `env()` çağırma — sadece `config/*.php` dosyalarında. Prod'da `config:cache` ile `env()` çağrıları null döner.

4. **`config/services.php`** her dış servis API key'i için tanım gerek:
   ```php
   'youtube' => ['key' => env('YOUTUBE_API_KEY')],
   'pagespeed' => ['api_key' => env('PAGESPEED_API_KEY')],
   ```

5. **APP_URL canlıda https:// olmalı.** Aksi takdirde `request()->isSecure()` false döner, SiteHealth "HTTPS aktif değil" der.

6. **APP_ENV ve APP_DEBUG canlıda:** `production` ve `false`. Hiçbir koşulda canlıda `APP_DEBUG=true` bırakma — `.env` değerleri ekrana sızar.

7. **public/build/ deploy gereksinimi:** `npm run build` çıktısı `public/build/` klasörüdür. Canlıda yoksa Vite manifest hatası alırsın. `.gitignore`'a dikkat — bu klasör commit edilmiyorsa ayrıca deploy et.

8. **CSP IPv6:** Vite dev server `localhost`'u `[::1]`'e resolve edebilir; CSP `[::1]` syntax'ını desteklemez. `vite.config.js`'te `server.host: '127.0.0.1'` zorla.

9. **Migration `Schema::table` vs `Schema::create`:** Dosya adı `create_X_table` ise `Schema::create` kullan; `add_Y_to_X_table` ise `Schema::table`. Karıştırınca "table doesn't exist" hatası alırsın.

10. **Page silme cascade:** `Page` silinince ilişkili `Seo`, `Gallery`, `FAQ`, `PageSchemas` kayıtları cascade ile silinmeli. Foreign key constraint kurulu mu kontrol et.

---

## 10. Kritik Dosyalar (Hızlı referans)

| Dosya | İçerik |
|---|---|
| `bootstrap/app.php` | Middleware + routing config (Laravel 12) |
| `config/services.php` | Dış servis API key tanımları |
| `vite.config.js` | Vite girdi dosyaları + dev server config |
| `resources/css/style.scss` | Ziyaretçi CSS (en büyük dosya, modüller dahil) |
| `resources/css/app.scss` | Panel CSS |
| `resources/js/style.js` | Ziyaretçi JS — Swiper/Fancybox/Flatpickr init'leri |
| `resources/views/user/partial/master.blade.php` | Ziyaretçi layout (head + content + footer) |
| `resources/views/user/partial/header.blade.php` | Ziyaretçi header (logo, menü, sosyal medya) |
| `resources/views/user/partial/mobile-menu.blade.php` | Mobile menu (vanilla JS controller) |
| `resources/views/cms/partial/layout.blade.php` | Panel layout |
| `app/Models/Page.php` | Ana içerik modeli — tüm sayfa türleri buradan türetilir |
| `app/Services/PageService.php` | Page CRUD + form işleme |
| `app/Services/SeoService.php` | SEO meta CRUD |
| `app/Http/Middleware/SecurityHeaders.php` | HSTS/CSP/X-Frame vs. header'lar |

---

## 11. Yeni Bir CMS Modülü Eklerken

1. **Migration** — `database/migrations/YYYY_MM_DD_create_xxx_table.php`
2. **Model** — `app/Models/Xxx.php` (relations + fillable + casts)
3. **Service** — `app/Services/XxxService.php` (CRUD + iş mantığı, array return)
4. **Controller** — `app/Http/Controllers/Cms/XxxController.php` (Service inject, ince katman)
5. **Route** — `routes/cms/xxx.php` + `routes/web.php`'ye include ekle
6. **View** — `resources/views/cms/xxx/{index,create,edit}.blade.php`
7. **Permission** — `permissions` tablosuna yeni izin satırları (cms.xxx.view, .create, .edit, .delete)
8. **Panel menüsü** — `cms/partial/sidebar.blade.php` veya `PanelMenuSetting` tablosuna ekle

---

## 12. AI Asistanı için Notlar

- **Memory dosyalarına saygı göster:** Bu projenin `.claude/` veya `memory/` klasöründe rol/feedback dosyaları olabilir.
- **Yıkıcı işlemler öncesi onay iste:** migrate, deploy, dosya silme, DB write.
- **Brand renkleri ve altın yasağına dikkat et.** Yeni component'lerde kırmızı+mavi kullan.
- **`config()` ile API key oku, `env()` ile değil.**
- **Vite dev server restart gerektiren durumlar:** `vite.config.js` değişikliği, yeni `import` eklenmesi (yeni npm paketi).
- **Hangi script.js?** Yeni JS init için **`resources/js/style.js`**. `public/js/user/script.js`'e dokunma.
- **CSP override:** Yeni CDN eklenince `SecurityHeaders.php`'deki ilgili directive'e ekle, yoksa "violates CSP" hatası alınır.
