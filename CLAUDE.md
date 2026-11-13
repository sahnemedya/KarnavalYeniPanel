# KarnavalYeniPanel — Mimari & Tasarım Rehberi

> **Proje kimliği:** Portakal Çiçeği Karnavalı (Adana) resmi web sitesi + yönetim paneli.
> **Tür:** Laravel 12 tabanlı çift yüzlü uygulama (ziyaretçi + CMS panel).
> **Önemli:** Diğer kardeş projeler (GaziCoskun, NupelInsaat vs.) ile aynı iskeleti paylaşır; ancak **bu proje için özel: karnaval sezonu / yıl yönetimi** var — site içeriğinin yıl bazlı sürümlenmesi (referanslar, sayfalar, sponsor dosyaları, sayaç) buna bağlı.

---

## 1. Tech Stack

| Katman | Teknoloji |
|---|---|
| Backend | PHP 8.3+, Laravel 12 |
| DB | MySQL 8+ |
| Asset pipeline | Vite 6 + SCSS (Dart Sass 1.89) |
| Frontend libs | jQuery 3.7, Owl Carousel (kalıntı), mmenu (kalıntı), Air Datepicker 3.3, Fancybox 5, Flatpickr, Dropzone, DataTables, Notyf, SweetAlert2 |
| Editör | CKEditor 4 (sayfa içeriği) |
| İkonlar | Line Awesome 1.3 (`la-*`) |
| SEO | butschster/meta-tags, spatie/laravel-sitemap, spatie/schema-org |
| Konum | stevebauman/location |

> **Not:** Bu proje **henüz** Owl → Swiper ve jQuery → vanilla geçişini yapmamıştır. `resources/js/style.js` içinde aktif olarak `$('#mobilmenu').mmenu(...)` ve `.owl-carousel-slider` init'leri vardır. Yeni slider/menü yazarken, kardeş projelerdeki modernizasyon referans değildir — projenin kendi mevcut stilini takip et, radikal refactor önerilmeden yapılmaz.

**Laravel 12 özel:** `app/Http/Kernel.php` **yok**. Middleware kaydı `bootstrap/app.php` içinde `$middleware->web(append: [...])` ve `$middleware->alias([...])` ile yapılır.

---

## 2. Klasör Yapısı (Yüksek seviye)

```
KarnavalYeniPanel/
├── app/
│   ├── Console/Commands/        # ClearAllLogs, GenerateLlmsTxt, MigrateLegacyData
│   ├── Http/
│   │   ├── Controllers/Cms/     # Panel controller'ları (~36 adet)
│   │   ├── Controllers/User/    # Ziyaretçi: UIndexController, ULanguageController
│   │   ├── Middleware/          # Authenticate, Geo, Language, Login kontrol, CheckPermission
│   │   └── Requests/            # FormRequest (KarnavalSezonuRequest vs.)
│   ├── Models/                  # Eloquent (~44 adet)
│   ├── Observers/               # Blade/Category/Language/Page/Seo/SiteSettings
│   ├── Providers/AppServiceProvider.php   # View composer'lar burada toplanıyor
│   ├── Services/                # İş mantığı (~37 adet)
│   └── Traits/
├── bootstrap/app.php            # Middleware + routing config (Laravel 12)
├── config/                      # services.php dahil
├── database/migrations/         # Tablo şemaları
├── database/seeders/            # DB seeder'lar
├── lang/{tr,en,fr}/             # Çeviri dosyaları (ortakMetinler.php vs.)
├── resources/
│   ├── css/
│   │   ├── app.scss             # Panel (CMS) — Vite girdisi
│   │   └── style.scss           # Ziyaretçi — Vite girdisi (~ana tasarım)
│   ├── js/
│   │   ├── app.js               # Panel JS
│   │   ├── style.js             # Ziyaretçi JS (owl/mmenu)
│   │   └── user/                # Eski kalıntı (ör. script.js varsa)
│   └── views/
│       ├── cms/                 # Panel blade'leri (modüle göre alt klasör)
│       │   ├── karnaval-sezonu/ # Karnaval yıl yönetimi (BU PROJEYE ÖZEL)
│       │   └── partial/         # layout, header, footer, head, ckeditor...
│       ├── user/                # Ziyaretçi blade'leri
│       │   ├── blades/          # Sayfa şablonları (~40 adet: normal, sponsorluk, oduller, galeri, vs.)
│       │   └── partial/         # master, header, footer, head, mobile-menu, sidemenu
│       └── errors/              # HTTP hata sayfaları
├── routes/
│   ├── web.php                  # Ana giriş — cms + user route'larını include eder
│   ├── auth.php, noAuth.php
│   ├── cms/                     # Panel route'ları (modül başına 1 dosya)
│   │   └── karnavalSezonu.php
│   └── user/index.php
├── public/
│   ├── build/                   # Vite output (deploy'da hazır olmalı)
│   ├── llms.txt                 # AI arama motorları için üretiliyor
│   ├── plugins/ckeditor/        # CKEditor 4 (eski, panel'den)
│   └── js/user/                 # Statik JS kalıntıları
├── karnavaldb (5).sql           # Son canlı DB dump (referans)
├── bilgiler.txt                 # Proje kurulum/deploy notları
├── vite.config.js
└── package.json
```

---

## 3. Backend Mimarisi (Service Pattern)

### 3.1 Akış

```
Request → Controller → Service → Model → DB
                          ↓
                   array dönüşü (status / message / data)
                          ↓
                  Controller → redirect-with veya JSON
```

### 3.2 Service dönüş konvansiyonu

Her service metodu **mutlaka** array döner:

```php
return [
    "status"  => "success",       // veya "error"
    "message" => "İşlem başarılı",
    "data"    => $payload,         // opsiyonel
];
```

Controller bu array'i `back()->with('status', $r['status'])->with('message', $r['message'])` veya `response()->json($r)` olarak işler.

### 3.3 Mevcut Service'ler

**Ortak (Kardeş projelerle aynı):**
`AntiSpamService`, `ApiKeyService`, `AramaKayitService`, `BladeService`, `CategoryService`, `CertificateService`, `CommonService` (resim/webp/dosya helper'ı), `ContactsService`, `FAQServices`, `GalleryService`, `GeminiService`, `GeoService` (llms.txt üretici), `LanguageService`, `LogService`, `NumberUsersService`, `PageSchemasService`, `PageService`, `PanelMenuSettingService`, `PermissionService`, `PopupService`, `PressKitService`, `ReferenceService`, `RolePermissionService`, `RoleUserService`, `RolesService`, `SeoService`, `SideMenuElementService`, `SiteSettingsService`, `SliderService`, `SocialMediaService`.

**Bu projeye özel / karnaval'a yoğun:**
`KarnavalSezonuService` — karnaval yılları CRUD + aktif sezon publish toggle (tek sezon aktif prensibi).
`AwardService` — ödüller (kraliçe adayları vs.)
`CommentService`, `DoctorService`, `FeatureService`, `PageVideoService`, `PodcastServices`, `ReferenceTypeService`.

### 3.4 Modeller (Karnaval'a Özel)

| Model | Rol |
|---|---|
| `KarnavalSezonu` | **Omurga** — her yılın karnaval bilgisi: `karnaval_yili`, `sezon_baslangici`, `karnaval_tarihi_baslangic`, `karnaval_tarihi_bitis`, `published` (tek aktif), `hit` (sıralama). SoftDeletes vardır. |
| `References` | Sponsorlar. `sezon_id` ile `KarnavalSezonu`'ya bağlı. Yıl bazlı sponsor listesi. |
| `Page` | Sayfa. İçinde `sezon_id` FK var — sayfaların yıl-bazlı versiyonlanması için. |
| `ReferenceType` | Sponsor tipleri (ana, destekçi, medya vs.) |
| `Slider`, `Popup`, `Gallery`, `PageVideo` | Yıl bazlı içerikler |
| `BalkonVitrinBasvuru`, `PortakalliLezzetler`, `RandevuAlForm`, `HumanResourceForm`, `ContactForm`, `BultenAbone`, `BultenIletisimFormu`, `MedyadaBiz`, `YetkiliAjanslar` | Karnaval'a özel form & içerik modelleri |
| `KullaniciGirisTakip`, `KullaniciYonlendirenSayfa`, `GonderilenMail` | Analytics/log |

### 3.5 Controller'lar

`app/Http/Controllers/Cms/` altında **36** controller. Her biri bir Service'i constructor'da inject eder:

```php
class KarnavalSezonuController extends Controller
{
    protected $karnavalSezonuService;

    function __construct(KarnavalSezonuService $karnavalSezonuService)
    {
        $this->karnavalSezonuService = $karnavalSezonuService;
    }
}
```

### 3.6 Middleware

| Middleware | Görev |
|---|---|
| `Authenticate` | Login kontrolü |
| `LoginPageAuthControl` | Zaten giriş yapmışsa login'e gitme |
| `CheckPermission` (`permission:slug`) | Yetki kontrolü |
| `GeoMiddleware` | IP → ülke + AI bot tespiti; AI bot'lara `X-Robots-Tag` ekler |
| `LanguageMiddleware` | Aktif dili `App::setLocale()` ile ayarlar |

> **DİKKAT:** `SecurityHeaders` middleware'i (HSTS/CSP) bu projede **YOK**. GaziCoskun pattern'inden farklı. Eğer header sertleştirme gerekirse önce ekibe sor, sonra ekle.

### 3.7 Route Yapısı

**Modüler.** `routes/web.php` → prefix `yonetim`, name `cms.` → `routes/cms/*.php` dosyaları include edilir. Ziyaretçi tarafı `routes/user/index.php` üzerinden `UIndexController`'a bağlı.

Route adlandırma: `cms.{module}.{action}` (örn. `cms.karnaval-sezonu.index`, `cms.karnaval-sezonu.activate`).

Dil değişimi: `GET /lang/switch/{langId}` → `ULanguageController::switchLanguage`.
Sitemap: `GET /sitemap.xml` → `UIndexController::sitemap`.

---

## 4. Karnaval Sezonu Sistemi (Bu projenin kalbi)

### 4.1 Tablo şeması: `karnaval_sezonus`

```sql
id              PK
hit             int         -- sıralama
karnaval_tarihi_baslangic  DATE
karnaval_tarihi_bitis      DATE
sezon_baslangici           DATE   -- sezon "başlangıcı" (karnaval tarihinden önce başlayan hazırlık)
karnaval_yili   STRING(100) -- '2026', '2027' vs. (string tutuluyor)
published       BOOL        -- aktif sezon (tek satır olmalı)
timestamps + softDeletes
```

### 4.2 Aktif sezon prensibi

`KarnavalSezonuService::activate($id)`:
- Bir sezonu `published=1` yaparken **önce diğer tüm sezonları `published=0`** yapar.
- Zaten aktifse toggle olarak pasife çeker.
- Aynı anda birden fazla aktif sezon olamaz.

```php
if ($karnavalSezonu->published == 1) {
    $karnavalSezonu->update(["published" => 0]);
} else {
    KarnavalSezonu::where('id', '!=', $id)->update(['published' => 0]);
    $karnavalSezonu->update(["published" => 1]);
}
```

### 4.3 İlişkiler

- `KarnavalSezonu hasMany References` (`sezon_id` FK)
- `KarnavalSezonu hasMany Pages` (`sezon_id` FK)

### 4.4 Ziyaretçi tarafında kullanımı

`AppServiceProvider::boot()` içinde `user.partial.footer` view composer'ı:

```php
$aktifSezon = KarnavalSezonu::where('published', 1)->first();
if ($aktifSezon) {
    $formatliTarih = $baslangic->day . ' - ' . $bitis->day . ' ' . $bitis->translatedFormat('F') . ' ' . $bitis->year;
} else {
    // Fallback: mevcut yıl + 1 "YIL'DA GÖRÜŞMEK ÜZERE!" mesajı
}
```

`UIndexController::sponsorlarimiz` sponsors sayfası, aktif sezon değil de **yıllara göre gruplu** sponsorları gösterir:

```php
->join('karnaval_sezonus', 'references.sezon_id', '=', 'karnaval_sezonus.id')
->orderBy('karnaval_sezonus.karnaval_yili', 'desc')
```

### 4.5 CMS panel ekranları

`resources/views/cms/karnaval-sezonu/`
- `index.blade.php` — Tüm sezonlar listesi, "Güncel Sezon" switch'i (tek aktif prensibi JS tarafında da görsellenir).
- `create.blade.php` — Air Datepicker ile 3 tarih + yıl dropdown.
- `edit.blade.php` — Düzenleme.
- `deleted.blade.php` — SoftDelete trash.

**Panel menü:** `cms/partial/header.blade.php` içinde `@permission('karnaval-sezonu')` bloğu ile görünür (tüm liste / oluştur / silinenler alt menüsü).

### 4.6 Form validasyonu

`app/Http/Requests/KarnavalSezonuRequest.php`:
```php
'karnaval_tarihi_baslangic' => ['required', 'date'],
'karnaval_tarihi_bitis'     => ['required', 'date', 'after_or_equal:karnaval_tarihi_baslangic'],
'sezon_baslangici'          => ['required', 'date'],
'karnaval_yili'             => ['required', 'string', 'max:100'],
'published'                 => ['nullable', 'boolean'],
```

### 4.7 Yeni Yıl Ekleme (2027 ve sonrası)

Bu proje için **sadece panele giriş yapıp "Oluştur" menüsünden** yıl eklenebilir. Fakat yılın hızlıca eklenmesi için artisan komut mevcuttur:

```bash
php artisan karnaval:yeni-sezon            # Mevcut yılın bir sonrasını ekler
php artisan karnaval:yeni-sezon 2027       # Belirli bir yıl ekler
php artisan karnaval:yeni-sezon 2027 --aktif   # Eklerken aktif de yapar
```

Komut tarihleri önceki yıldan +1 yıl ileriye alır, henüz yoksa oluşturur, varsa uyarı verir. Detay: `app/Console/Commands/AddKarnavalSezonu.php`.

---

## 5. SEO + GEO (Generative Engine Optimization)

`pages` ↔ `seos` (1-1). `Seo` modelinde SEO meta + GEO alanları (`geo_title`, `geo_description`).

`GeoService::generateLlmsTxt()` → Google AI bot'ları için `public/llms.txt` üretir. `Page::withGeo()` scope'u `with('seo')` eager-load eder.

`GeoMiddleware`, user-agent'ta `chatgpt-user`, `gptbot`, `claude-web`, `anthropic-ai` görürse yanıta özel header'lar ekler.

---

## 6. API Key Yönetimi

`api_keys` tablosu — panel'den yönetilen tek satırlı ayar. **Her ayar ayrı kolon** (JSON değil).

**Panel'den gelen key'ler (`ApiKeys::first()`):** `youtube_channel_id`, `google_place_id`, `google_places_api_key`, `pagespeed_api_key`, `clarity_project_id`, `google_analytics_id`, `recaptcha_site_key`, `recaptcha_secret_key`.

**`.env`'den gelen key'ler (`config('services.xxx.key')`):** `YOUTUBE_API_KEY`, `GEMINI_API_KEY`, DB & MAIL credentials.

⚠ `config/services.php` içinde tanımlanmamış bir servis için `config()` null döner. Yeni servis eklenince orayı güncelle.

---

## 7. Çoklu Dil Desteği

Mevcut diller: **Türkçe (varsayılan)**, İngilizce, Fransızca.

- `languages` tablosu — aktif dilleri tutar
- `pages.translation_of` self-FK — ana dil sayfasından çeviri sayfasına
- `Session::get('uLang')` ile aktif dil cache'lenir
- `lang/{tr,en,fr}/ortakMetinler.php` — template'teki sabit metinlerin çevirileri

`AppServiceProvider` içinde her view'a `$activeLanguages`, `$currentLang`, `$languages` composer'ı ile enjekte edilir.

---

## 8. Frontend (Ziyaretçi) — Tasarım Dili

### 8.1 İki ayrı asset grubu

| Taraf | View | SCSS | JS |
|---|---|---|---|
| **Panel (CMS)** | `resources/views/cms/` | `resources/css/app.scss` | `resources/js/app.js` |
| **Ziyaretçi** | `resources/views/user/` | `resources/css/style.scss` | `resources/js/style.js` |

Vite config: `laravel-vite-plugin` ile dört girdi (`app.scss`, `app.js`, `style.scss`, `style.js`).

### 8.2 Renk paleti (Ziyaretçi — `style.scss`)

```scss
// Ana marka renkleri (karnaval kimliği)
$yesil:     #103d47;   // koyu petrol/deniz yeşili — header font, kutu çerçeve
$yesil2:    #002731;   // daha koyu — hover / dipler
$turuncu:   #e28337;   // portakal — primary CTA, vurgu
$turuncu2:  #f38128;   // alternatif portakal — hover

// Alternatif sarı (eski sponsorluk sayfasında)
#efa506                 // başlık + "sponsor dosyası" butonları (sponsorluk.blade.php içi)
#ee7e23                 // ikincil turuncu (owl nav)

// İletişim butonları (sponsorluk.blade.php)
#03a29c  → .tel
#156ed6  → .mail
#1da960  → .wp (whatsapp)
#ee7e23  → .pr

// Nötr
$beyaz:     #fbfbfb
$siyah:     #000000
$gri:       #767676
$acikbeyaz: rgba(255,255,255,0.8)
$antrasit:  rgb(67,67,67)

// Header
$header-bg:                 #ffffff
$header-font-color:         #111111
$header-font-hover:         $turuncu2 (#f38128)
$header-font-shadow:        rgba(226, 131, 55, 0.2)
```

**Kullanım kuralları:**
- Marka kimliği: **koyu petrol yeşili + portakal turuncusu** ikilisi. Bu projede altın/sarı (`#efa506`) **sadece sponsorluk sayfasında** meşru bir kullanım.
- Portakal (karnaval sembolü) → her CTA, butonlar, hover'lar.
- Petrol yeşili → başlıklar, çerçeveler, dipnotlar, footer BG.
- **Kırmızı yasak** (kardeş GaziCoskun projesinin tersine burada kırmızı kullanılmaz).

### 8.3 Tipografi

```scss
Manrope        → gövde metin, bilgilendirici yazılar ($metin)
Inter          → başlık, header, iç başlık ($baslik / $headerbaslik / $icbaslik)
Sour Gummy     → import edildi ama şu anda aktif değil (gelecek için)
```

Font tanımları `style.scss` başında Google Fonts'tan import.

### 8.4 Layout değişkenleri

```scss
$max-width:              1440px       // içerik kabuğu
$main-top-space:         55px
$main-bottom-space:      65px
$main-mobil-top-space:   35px
$main-mobil-bottom-space:35px

.max-width {
    max-width: $max-width !important;
    margin: 0 auto;
    padding: 0 25px;
    @media (max-width: 768px) { padding: 0 10px; }
}

.content-space { padding: $main-top-space 0 $main-bottom-space 0; }
.main-space    { margin-top: $main-top-space; margin-bottom: $main-bottom-space; }
```

### 8.5 Kırılma noktaları (breakpoint'ler)

```scss
(max-width: 1440px)    // büyük masaüstü
(max-width: 1024px)    // küçük masaüstü / tablet landscape
(max-width: 768px)     // tablet portrait
(max-width: 500px)     // mobil
(max-width: 425px)     // küçük mobil
```

### 8.6 Buton stili (ortak)

- CTA butonları: `background: $turuncu`; `color: #fff`; `transition: all 0.3s ease-in-out`
- Hover'da BG değişir (çoğunlukla koyulaşır veya `$yesil` olur)
- Köşe radius: kullanılmıyor (keskin köşeler — karnaval dinamizmi için)
- Sponsor butonları (`sponsorluk.blade.php`): `background: #efa506` + hover `#1f2a3f`

### 8.7 Transition

Global olarak `* { transition: all 0.5s ease-in-out; }` tanımlı — tüm elementlerde yumuşak geçiş var. Bu bazen beklenmedik yavaşlıklar yaratabilir; kritik performans endişesinde `transition: none` ile override et.

### 8.8 Blade şablonları (sayfa tipi seçici)

`blades` tablosunda kayıtlı şablonlar `resources/views/user/blades/` altında. Her `Page` bir `blade_id` ile şablon seçer. Mevcut şablonlar:

```
normal                               (standart sayfa)
normal-menusuz
sponsorlarimiz / sponsorlarimiz-detay
sponsorluk
oduller
galeri / fotograf-arsivi / video-arsivi
gazete / gazete-detay
blog / blog-detay
duyurular / duyurular-detay
iletisim / randevu-al / insan-kaynaklari / balkon-vitrin-yarisma / portakalli-lezzetler
yetkili-ajanslar / basin-odasi
adanada-* (turizm/gezi sayfaları)
klinik / tibbi-ikinci-gorus
surdurulebilirlik-raporlari / surdurulebilirlik-videolari
gecmis-seneler / sertifikalar / podcast / orman-detay
```

Her sayfa Blade şablonu, `user.partial.master` layout'unu extend eder (head + content + footer).

---

## 9. CMS Panel — Tasarım

Panel ayrı SCSS (`app.scss`) ve ayrı layout (`cms.partial.layout`). Kardeş projelerle aynı pattern:

- Line Awesome ikonları (`la-pen`, `la-trash`, `la-recycle`...)
- DataTables ile listeleme
- Air Datepicker / Flatpickr ile tarih
- CKEditor 4 ile zengin metin
- Notyf ile toast bildirim
- Switch (`<label class="switch"><input type="checkbox"><span class="switch-slider"></span></label>`) ile toggle
- Buton sınıfları: `btn bg-primary` (düzenle), `btn bg-error` (sil), `btn bg-warning` (geri yükle)

---

## 10. Sayfa Oluşturma Akışı (Yeni bir sayfa / modül eklerken)

> Sırası: **Model → Services → Controllers → View → Route** (bilgiler.txt notu)

1. **Migration** → `database/migrations/YYYY_MM_DD_create_xxx_table.php`
2. **Model** → `app/Models/Xxx.php` (fillable, relations, casts; `SoftDeletes` kullan)
3. **Service** → `app/Services/XxxService.php` (array return konvansiyonu + LogService)
4. **FormRequest** (gerekirse) → `app/Http/Requests/XxxRequest.php`
5. **Controller** → `app/Http/Controllers/Cms/XxxController.php` (Service inject)
6. **Route** → `routes/cms/xxx.php` + `routes/web.php`'ye include ekle
7. **View** → `resources/views/cms/xxx/{index,create,edit,deleted}.blade.php`
8. **Permission** → `permissions` tablosuna slug ekle (seeder veya manuel); menüyü `cms/partial/header.blade.php`'de `@permission('xxx')` ile sar
9. **Panel menüsü** → `cms/partial/header.blade.php`'ye link ekle
10. **(İsteğe bağlı)** `User` tarafı eklenecekse `routes/user/index.php` + `UIndexController` metodu + `user/blades/*.blade.php`

---

## 11. Konvansiyonlar

### 11.1 Dil
- **Kod:** İngilizce (sınıf/değişken/method)
- **Mesaj / log / blade:** Türkçe
- **Yorum:** Türkçe (çoğunluk) veya İngilizce

### 11.2 LogService kullanımı
```php
LogService::add("Modül Action", "success", "Mesaj");
LogService::add("Modül Action Error", "error", "Hata: " . $e->getMessage());
```

### 11.3 Route adlandırma
`cms.{module}.{action}` — snake-case'li modül adı de olabilir (`karnaval-sezonu`).

### 11.4 View klasör adı
`snake_case_klasor` veya `kebab-case` (örn. `karnaval-sezonu`, `references`, `human_resource` karışık — mevcut yapıya uy).

### 11.5 Permission slug'ı
Modül adıyla aynı (örn. `karnaval-sezonu`). `@permission('karnaval-sezonu')` ile kullan.

---

## 12. Özel Artisan Komutları

| Komut | Görev |
|---|---|
| `php artisan logs:clear-all` | `cdr.log` ve `laravel.log` dosyalarını temizler (ClearAllLogs) |
| `php artisan karnaval:yeni-sezon [yil] [--aktif]` | Yeni karnaval sezonu ekler (bu projeye özel) |
| `php artisan` komutu — GenerateLlmsTxt | `llms.txt` üretir |
| `php artisan` komutu — MigrateLegacyData | Eski verilerin taşınması (bir kerelik) |

---

## 13. Sık Karşılaşılan Tuzaklar

1. **Marka renkleri:** Petrol yeşili (`#103d47`, `#002731`) + portakal (`#e28337`, `#f38128`). Kırmızı YASAK, altın/sarı sadece sponsorluk sayfasında.

2. **jQuery ve Owl burada hâlâ aktif:** `resources/js/style.js` içinde `$('#mobilmenu').mmenu(...)` ve `.owl-carousel-slider` var. Kardeş projelerdeki Swiper geçişi burada yapılmadı — mevcut stili bozacak refactor önerilmeden yapılmaz.

3. **`config()` vs `env()`:** Kod içinde **asla** `env()` çağırma — sadece `config/*.php`. Prod'da `config:cache` sonrası `env()` null döner.

4. **`config/services.php` tanımı:** Her dış servis API key'i için servis tanımla:
   ```php
   'youtube'   => ['key' => env('YOUTUBE_API_KEY')],
   'pagespeed' => ['api_key' => env('PAGESPEED_API_KEY')],
   ```

5. **Karnaval sezonu `published` tekliği:** Birden fazla sezonu aktif yapma. `KarnavalSezonuService::activate()` zaten tek aktif prensibini uygular; manuel DB düzenlerken dikkat.

6. **`karnaval_yili` tipi string:** `karnaval_yili` sütunu `varchar(100)`. Integer karşılaştırmasında `(int) $item->karnaval_yili` kullan.

7. **Air Datepicker tarih formatı:** Görsel alan `dd MMMM yyyy` (örn. `05 Mart 2026`), backend'e giden gizli input `yyyy-MM-dd`. `altField` ve `altFieldDateFormat` doğru ayarlı olmalı — tersi olursa DATE kolonuna yanlış değer gider.

8. **`APP_URL`:** Canlıda `https://` ile başlamalı. Localhost'ta `http://localhost` veya `http://127.0.0.1:8000`.

9. **`APP_ENV` / `APP_DEBUG`:** Canlıda **kesinlikle** `production` / `false`. Aksi halde .env sızar.

10. **`public/build/` deploy:** `npm run build` sonrası oluşan `public/build/` canlıda var olmalı. Vite manifest hatası = `npm run build` eksik.

11. **`storage:link`:** Resim upload'ları `storage/app/public` altına düşer; `public/storage` sembolik link olmadan görüntülenmez. İlk deploy sonrası `php artisan storage:link` çalıştır.

12. **Edit formunda `$page` referansı:** `cms/karnaval-sezonu/edit.blade.php` 22. satırda `{{$page->hit}}` kullanılmış ama controller `$karnavalSezonlari` veriyor. Fırsat bulunca `{{$karnavalSezonlari->hit}}` olarak düzelt (var olan hata).

13. **Türkçe karakter + `translatedFormat`:** Carbon locale footer composer'ında `tr`/`en`/`fr` olarak ayarlanıyor. Yeni dil eklerken `AppServiceProvider::boot()` içindeki `match` bloğunu güncelle.

14. **CKEditor 4 eski:** `public/plugins/ckeditor/` altında yerel kurulu (CKEditor 5 değil). Yeni sayfa editörü eklenince aynı CDN pattern'ini kullan.

---

## 14. Kritik Dosyalar (Hızlı referans)

| Dosya | İçerik |
|---|---|
| `bootstrap/app.php` | Middleware + routing config (Laravel 12) |
| `routes/web.php` | Ana route girişi — cms ve user route'ları include eder |
| `app/Providers/AppServiceProvider.php` | Tüm view composer'lar (dil, menü, footer, API key'ler) |
| `app/Models/KarnavalSezonu.php` | Karnaval yıl modeli (bu projenin kalbi) |
| `app/Services/KarnavalSezonuService.php` | Karnaval sezon CRUD + activate toggle |
| `app/Http/Requests/KarnavalSezonuRequest.php` | Karnaval sezon validasyon |
| `app/Console/Commands/AddKarnavalSezonu.php` | **Yeni:** Hızlı yıl ekleme komutu |
| `resources/css/style.scss` | Ziyaretçi ana SCSS (tüm marka renkleri + tipografi burada) |
| `resources/css/app.scss` | Panel SCSS |
| `resources/js/style.js` | Ziyaretçi JS (owl + mmenu) |
| `resources/views/user/partial/master.blade.php` | Ziyaretçi layout |
| `resources/views/cms/partial/layout.blade.php` | Panel layout |
| `resources/views/cms/partial/header.blade.php` | Panel menüsü (üst nav) |
| `database/migrations/2025_05_10_164414_create_karnaval_sezonus_table.php` | Karnaval sezon tablo şeması |

---

## 15. AI Asistanı için Notlar

- **Yıkıcı işlem öncesi onay iste:** migrate, deploy, dosya silme, DB write.
- **Marka renklerine saygı:** Petrol yeşili + portakal. Kırmızı yok, altın sadece sponsorluk sayfasında.
- **`config()` ile API key oku, `env()` ile değil.**
- **jQuery / Owl / mmenu burada hâlâ aktif** — "modernize edeyim" diye kardeş projelerin Swiper geçişini uygulama.
- **Karnaval sezonu `published` tekliği** — sadece tek sezon aktif olabilir, bu kural manuel DB edit'lerde de korunmalı.
- **Memory dosyalarına saygı göster:** Proje `memory/` klasöründe rol/feedback dosyaları olabilir.
- **Yeni yıl eklenecekse:** `php artisan karnaval:yeni-sezon` komutunu kullan, elle SQL dokundurma.
