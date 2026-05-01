<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\AramaKayit;
use App\Models\ContactForm;
use App\Models\NumberUsers;
use App\Models\CheckUpForm;
use App\Models\DoktorunuzaSorunForm;
use App\Models\HealthTourismForm;
use App\Models\HumanResourceForm;
use App\Models\MemnuniyetAnketi;
use App\Models\OneriveSikayetFormu;
use App\Models\OneStopMemeForm;
use App\Models\OneStopTiroidForm;
use App\Models\RandevuAlForm;
use App\Models\RobotikCerrahiForm;
use App\Models\TibbiBirimlerForm;
use App\Models\TibbiIkinciGorusForm;
use App\Services\CommonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

// Veritabanı sorguları için eklendi

class IndexController extends Controller
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index()
    {
        Carbon::setLocale('tr_TR');

        // OPTİMİZASYON 1: Cache süresini artırdık (Örn: 180 dakika = 3 saat)
        // Eğer veriyi anlık görmek istemiyorsanız bu süre idealdir.
        $cacheKey = 'cms_dashboard_optimized_v12';
        $cacheDuration = 60; // Saniye cinsinden 3 saat (Laravel sürümüne göre dakika da olabilir, genellikle saniyedir)

        $data = Cache::remember($cacheKey, $cacheDuration, function () {

            $now = Carbon::now();

            // Tarih sınırlarını önceden belirleyelim
            $dates = [
                'd3' => $now->copy()->subDays(3),
                'd7' => $now->copy()->subDays(7),
                'm1' => $now->copy()->subMonth(),
                'm3' => $now->copy()->subMonths(3),
                'm6' => $now->copy()->subMonths(6),
                'm9' => $now->copy()->subMonths(9),
                'm12' => $now->copy()->subMonths(12),
            ];

            // =====================================================================
            // OPTİMİZASYON 2: Conditional Aggregation (Tek Sorguda Tüm Zamanlar)
            // =====================================================================

            // NumberUsers için 8 sorgu yerine TEK sorgu atıyoruz.
            // "updated_at şu tarihten büyükse say, değilse null geç" mantığı.
            $userStats = NumberUsers::selectRaw("
                COUNT(DISTINCT ip) as tum_kullanici,
                COUNT(DISTINCT CASE WHEN updated_at >= ? THEN ip END) as son_3_gun,
                COUNT(DISTINCT CASE WHEN updated_at >= ? THEN ip END) as son_7_gun,
                COUNT(DISTINCT CASE WHEN updated_at >= ? THEN ip END) as son_1_ay,
                COUNT(DISTINCT CASE WHEN updated_at >= ? THEN ip END) as son_3_ay,
                COUNT(DISTINCT CASE WHEN updated_at >= ? THEN ip END) as son_6_ay,
                COUNT(DISTINCT CASE WHEN updated_at >= ? THEN ip END) as son_9_ay,
                COUNT(DISTINCT CASE WHEN updated_at >= ? THEN ip END) as son_12_ay
            ", [
                $dates['d3'], $dates['d7'], $dates['m1'], $dates['m3'],
                $dates['m6'], $dates['m9'], $dates['m12']
            ])->first();

            $kullaniciSayilari = $userStats->toArray();

            // Arama Kayıtları (Whatsapp ve Telefon) için TEK sorgu
            // Hem türüne göre hem tarihine göre grupluyoruz.
            $callStats = AramaKayit::selectRaw("
                tur,
                COUNT(*) as total,
                SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_3_gun,
                SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_7_gun,
                SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_1_ay,
                SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_3_ay,
                SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_6_ay,
                SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_9_ay,
                SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_12_ay
            ", [
                $dates['d3'], $dates['d7'], $dates['m1'], $dates['m3'],
                $dates['m6'], $dates['m9'], $dates['m12']
            ])->groupBy('tur')->get()->keyBy('tur');

            // Helper fonksiyonu kullanmak yerine veriyi buradan çekiyoruz
            $whatsappAramaSayilari = $callStats['Whatsapp'] ?? [
                'son_3_gun' => 0, 'son_7_gun' => 0, 'son_1_ay' => 0,
                'son_3_ay' => 0, 'son_6_ay' => 0, 'son_9_ay' => 0,
                'son_12_ay' => 0, 'total' => 0
            ];
            // Key uyuşmazlığını düzeltelim (total -> tum_whatsapp_aramalari)
            $whatsappAramaSayilari = is_object($whatsappAramaSayilari) ? $whatsappAramaSayilari->toArray() : $whatsappAramaSayilari;
            $whatsappAramaSayilari['tum_whatsapp_aramalari'] = $whatsappAramaSayilari['total'] ?? 0;
            unset($whatsappAramaSayilari['total']);

            $telefonAramaSayilari = $callStats['Telefon'] ?? [
                'son_3_gun' => 0, 'son_7_gun' => 0, 'son_1_ay' => 0,
                'son_3_ay' => 0, 'son_6_ay' => 0, 'son_9_ay' => 0,
                'son_12_ay' => 0, 'total' => 0
            ];
            $telefonAramaSayilari = is_object($telefonAramaSayilari) ? $telefonAramaSayilari->toArray() : $telefonAramaSayilari;
            $telefonAramaSayilari['tum_whatsapp_aramalari'] = $telefonAramaSayilari['total'] ?? 0; // İsimlendirme standardını korudum
            unset($telefonAramaSayilari['total']);


            // Form İstatistikleri
            $formModels = [
                'ContactForm' => ContactForm::class,
            ];

            $formVerileri = [];
            $tumMailToplami = 0;

            // Toplamlar dizisini başta sıfırla başlatalım
            $toplamMailVerisi = [
                'son_3_gun' => 0, 'son_7_gun' => 0, 'son_1_ay' => 0,
                'son_3_ay' => 0, 'son_6_ay' => 0, 'son_9_ay' => 0,
                'son_12_ay' => 0, 'tum_mail' => 0
            ];

            foreach ($formModels as $isim => $model) {
                // OPTİMİZASYON: Her form için 8 sorgu yerine 1 sorgu
                $stats = $model::selectRaw("
                    COUNT(*) as tum_mail,
                    SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_3_gun,
                    SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_7_gun,
                    SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_1_ay,
                    SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_3_ay,
                    SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_6_ay,
                    SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_9_ay,
                    SUM(CASE WHEN updated_at >= ? THEN 1 ELSE 0 END) as son_12_ay
                ", [
                    $dates['d3'], $dates['d7'], $dates['m1'], $dates['m3'],
                    $dates['m6'], $dates['m9'], $dates['m12']
                ])->first()->toArray();

                $formVerileri[$isim] = $stats;
                $tumMailToplami += $stats['tum_mail'];

                // Toplamlar dizisini de döngü içinde toplayarak array_sum yükünden kurtaralım
                foreach ($stats as $key => $val) {
                    if(isset($toplamMailVerisi[$key])) {
                        $toplamMailVerisi[$key] += $val;
                    }
                }
            }

            // Grafik Verileri (Bunlar zaten optimize edilmişti, çağıralım)
            $monthlyReportData = $this->getMonthlyReportData($formModels ?? []);
            $weeklyReportData = $this->getWeeklyReportData($formModels ?? []);

            // Şehir Verileri
            // Bu kısım zaten optimizeydi, cache içine aldığımız için artık daha hızlı çalışacak.
            $cityData = $this->getCityCountryData();

            return array_merge(compact(
                'kullaniciSayilari',
                'whatsappAramaSayilari',
                'telefonAramaSayilari',
                'formVerileri',
                'toplamMailVerisi',
                'monthlyReportData',
                'weeklyReportData'
            ), $cityData);
        });

        return view('cms.index', $data);
    }

    // Şehir ve Ülke verilerini ayırıp private metoda aldım (Kod temizliği için)
    private function getCityCountryData() {
        $turkeyCities = [
            'Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Artvin', 'Aydın', 'Balıkesir',
            'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 'Çanakkale', 'Çankırı', 'Çorum', 'Denizli',
            'Diyarbakır', 'Edirne', 'Elazığ', 'Erzincan', 'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkari',
            'Hatay', 'Isparta', 'Mersin', 'İstanbul', 'İzmir', 'Kars', 'Kastamonu', 'Kayseri', 'Kırklareli', 'Kırşehir',
            'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Kahramanmaraş', 'Mardin', 'Muğla', 'Muş', 'Nevşehir',
            'Niğde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop', 'Sivas', 'Tekirdağ', 'Tokat',
            'Trabzon', 'Tunceli', 'Şanlıurfa', 'Uşak', 'Van', 'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman',
            'Kırıkkale', 'Batman', 'Şırnak', 'Bartın', 'Ardahan', 'Iğdır', 'Yalova', 'Karabük', 'Kilis', 'Osmaniye', 'Düzce'
        ];

        $cityUserCounts = NumberUsers::select('sehir', DB::raw('count(DISTINCT ip) as total'))
            ->whereNotNull('sehir')
            ->groupBy('sehir')
            ->pluck('total', 'sehir')
            ->toArray();

        $cityInteractions = DB::table('arama_kayits')
            ->join('number_users', 'arama_kayits.ip', '=', 'number_users.ip')
            ->select('number_users.sehir', 'arama_kayits.tur', DB::raw('count(*) as total'))
            ->whereNotNull('number_users.sehir')
            ->groupBy('number_users.sehir', 'arama_kayits.tur')
            ->get();

        $interactionsMap = [];
        foreach ($cityInteractions as $row) {
            $interactionsMap[$row->sehir][$row->tur] = $row->total;
        }

        $detailedCityData = [];
        foreach ($turkeyCities as $cityName) {
            $detailedCityData[] = [
                'isim' => $cityName,
                'kullanici' => $cityUserCounts[$cityName] ?? 0,
                'whatsapp' => $interactionsMap[$cityName]['Whatsapp'] ?? 0,
                'telefon' => $interactionsMap[$cityName]['Telefon'] ?? 0,
                'mail' => 0
            ];
        }

        $countrySqlCase = "CASE WHEN ulke = 'Turkey' THEN 'Türkiye' ELSE ulke END";

        // 1. Kullanıcı Sayıları (IP)
        $countryUserCounts = NumberUsers::select(
            DB::raw("$countrySqlCase as ulke_adi"),
            DB::raw('count(DISTINCT ip) as total')
        )
            ->whereNotNull('ulke')
            ->groupBy(DB::raw($countrySqlCase)) // Gruplarken de aynı mantığı kullanıyoruz
            ->pluck('total', 'ulke_adi')
            ->toArray();

        // 2. Etkileşimler (Whatsapp/Telefon)
        $countryInteractions = DB::table('arama_kayits')
            ->join('number_users', 'arama_kayits.ip', '=', 'number_users.ip')
            ->select(
                DB::raw("$countrySqlCase as ulke_adi"),
                'arama_kayits.tur',
                DB::raw('count(*) as total')
            )
            ->whereNotNull('number_users.ulke')
            ->groupBy(DB::raw($countrySqlCase), 'arama_kayits.tur')
            ->get();

        // Haritalama (Mapping)
        $countryMap = [];
        foreach ($countryInteractions as $row) {
            // Artık $row->ulke değil, alias verdiğimiz $row->ulke_adi gelecek
            $countryMap[$row->ulke_adi][$row->tur] = $row->total;
        }

        // 3. Tabloyu Oluştur
        $detailedCountryData = [];
        foreach ($countryUserCounts as $countryName => $count) {
            $detailedCountryData[] = [
                'isim' => $countryName, // Artık burada sadece 'Türkiye' olacak, 'Turkey' gelmeyecek
                'kullanici' => $count,
                'whatsapp' => $countryMap[$countryName]['Whatsapp'] ?? 0,
                'telefon' => $countryMap[$countryName]['Telefon'] ?? 0,
                'mail' => 0
            ];
        }

        return [
            'detailedCityData' => collect($detailedCityData)->sortByDesc('kullanici')->values(),
            'detailedCountryData' => collect($detailedCountryData)->sortByDesc('kullanici')->values()
        ];
    }

    /**
     * GÜNCELLENDİ: Bir önceki ayın verileri de hesaplanıp return dizisine eklendi.
     */
    private function getMonthlyReportData(array $formModels)
    {
        // -----------------------------------------------------
        // 1. MEVCUT AY İŞLEMLERİ (Aynen korundu)
        // -----------------------------------------------------
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $userLogins = NumberUsers::select(DB::raw('DATE(updated_at) as date'), DB::raw('COUNT(DISTINCT ip) as count'))
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        $realTotalUsers = NumberUsers::whereBetween('updated_at', [$startDate, $endDate])
            ->distinct('ip')
            ->count('ip');

        $whatsappCalls = AramaKayit::select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
            ->where('tur', 'Whatsapp')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        $phoneCalls = AramaKayit::select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
            ->where('tur', 'Telefon')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        $totalMails = collect();
        foreach ($formModels as $model) {
            $mails = $model::select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->groupBy('date')
                ->pluck('count', 'date');

            $mails->each(function ($count, $date) use (&$totalMails) {
                $currentCount = $totalMails->get($date, 0);
                $totalMails->put($date, $currentCount + $count);
            });
        }

        $period = CarbonPeriod::create($startDate, Carbon::now());
        $days_data = [];
        $totals = [
            'total_logins' => $realTotalUsers,
            'total_whatsapp' => 0,
            'total_phone' => 0,
            'total_mails' => 0
        ];

        foreach ($period as $date) {
            $dateString = $date->toDateString();

            $loginsCount = $userLogins->get($dateString, 0);
            $whatsappCount = $whatsappCalls->get($dateString, 0);
            $phoneCount = $phoneCalls->get($dateString, 0);
            $mailsCount = $totalMails->get($dateString, 0);

            $days_data[] = [
                'date_formatted' => $date->translatedFormat('d F'),
                'day_name' => $date->translatedFormat('l'),
                'user_logins' => $loginsCount,
                'whatsapp' => $whatsappCount,
                'phone' => $phoneCount,
                'mails' => $mailsCount,
            ];

            $totals['total_whatsapp'] += $whatsappCount;
            $totals['total_phone'] += $phoneCount;
            $totals['total_mails'] += $mailsCount;
        }

        // -----------------------------------------------------
        // 2. BİR ÖNCEKİ AY VERİLERİ (YENİ EKLENDİ)
        // -----------------------------------------------------
        // Önceki ayın başlangıcı ve bitişi
        $prevStartDate = Carbon::now()->subMonth()->startOfMonth();
        $prevEndDate = Carbon::now()->subMonth()->endOfMonth();

        // Önceki Ay: Kullanıcı Sayısı (Tekil IP)
        $prevTotalUsers = NumberUsers::whereBetween('updated_at', [$prevStartDate, $prevEndDate])
            ->distinct('ip')
            ->count('ip');

        // Önceki Ay: Whatsapp
        $prevTotalWhatsapp = AramaKayit::where('tur', 'Whatsapp')
            ->whereBetween('updated_at', [$prevStartDate, $prevEndDate])
            ->count();

        // Önceki Ay: Telefon
        $prevTotalPhone = AramaKayit::where('tur', 'Telefon')
            ->whereBetween('updated_at', [$prevStartDate, $prevEndDate])
            ->count();

        // Önceki Ay: Mailler (Tüm form modelleri)
        $prevTotalMails = 0;
        foreach ($formModels as $model) {
            $prevTotalMails += $model::whereBetween('updated_at', [$prevStartDate, $prevEndDate])->count();
        }

        return [
            'month_name' => Carbon::now()->translatedFormat('F'),
            'days_data' => $days_data,
            'totals' => $totals,
            // Yeni eklenen veri bloğu
            'previous_month' => [
                'name' => $prevStartDate->translatedFormat('F'), // Örn: Kasım
                'total_users' => $prevTotalUsers,
                'total_whatsapp' => $prevTotalWhatsapp,
                'total_phone' => $prevTotalPhone,
                'total_mails' => $prevTotalMails,
            ]
        ];
    }

    /**
     * Kümülatif Haftalık Rapor
     * Ay içindeki tüm Pazartesileri toplar Pazartesiye yazar, tüm Salıları toplar Salıya yazar vb.
     */
    private function getWeeklyReportData(array $formModels)
    {
        // 1. TARİH ARALIĞI: AYIN BAŞINDAN SONUNA
        // Ay içindeki tüm aynı günleri yakalamak için ay bazlı çekiyoruz.
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // ---------------------------------------------------------
        // VERİTABANI SORGULARI (Date Grouping)
        // ---------------------------------------------------------

        // A. Kullanıcı Girişleri (IP Sayar - DISTINCT IP)
        $rawUserLogins = NumberUsers::select(
            DB::raw('DATE(DATE_ADD(updated_at, INTERVAL 3 HOUR)) as date'),
            DB::raw('COUNT(DISTINCT ip) as count')
        )
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        // B. WhatsApp Aramaları (Adet Sayar - count(*))
        $rawWhatsappCalls = AramaKayit::select(
            DB::raw('DATE(DATE_ADD(updated_at, INTERVAL 3 HOUR)) as date'),
            DB::raw('count(*) as count')
        )
            ->where('tur', 'Whatsapp')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        // C. Telefon Aramaları (Adet Sayar - count(*))
        $rawPhoneCalls = AramaKayit::select(
            DB::raw('DATE(DATE_ADD(updated_at, INTERVAL 3 HOUR)) as date'),
            DB::raw('count(*) as count')
        )
            ->where('tur', 'Telefon')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date');

        // D. Form Mailleri (Adet Sayar - count(*))
        $rawMails = collect();
        foreach ($formModels as $model) {
            $mails = $model::select(
                DB::raw('DATE(DATE_ADD(updated_at, INTERVAL 3 HOUR)) as date'),
                DB::raw('count(*) as count')
            )
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->groupBy('date')
                ->pluck('count', 'date');

            // Mailleri tek bir koleksiyonda birleştiriyoruz
            $mails->each(function ($count, $date) use (&$rawMails) {
                $currentCount = $rawMails->get($date, 0);
                $rawMails->put($date, $currentCount + $count);
            });
        }

        // ---------------------------------------------------------
        // GÜNLERE GÖRE TOPLAMA (KÜMÜLATİF MANTIK)
        // ---------------------------------------------------------

        // 1=Pazartesi, 2=Salı ... 7=Pazar şeklinde boş kutular hazırlıyoruz.
        $cumulativeDays = [];
        for ($i = 1; $i <= 7; $i++) {
            $cumulativeDays[$i] = [
                'user_logins' => 0,
                'whatsapp' => 0,
                'phone' => 0,
                'mails' => 0,
            ];
        }

        // Bu fonksiyon, veritabanından gelen tarihleri gününe göre kutulara ekler.
        // Örn: Gelen veri '2025-11-03' (Pazartesi) ise 1. kutuya ekler.
        // Örn: Gelen veri '2025-11-10' (Pazartesi) ise yine 1. kutuya ekler (Toplar).
        $distributeToDays = function ($dataSet, $typeKey) use (&$cumulativeDays) {
            foreach ($dataSet as $dateStr => $count) {
                $dateObj = Carbon::parse($dateStr);
                $dayIndex = $dateObj->dayOfWeekIso; // 1 (Pzt) - 7 (Paz) arası değer döner

                if (isset($cumulativeDays[$dayIndex])) {
                    $cumulativeDays[$dayIndex][$typeKey] += $count;
                }
            }
        };

        // Tüm verileri günlerine dağıtıyoruz
        $distributeToDays($rawUserLogins, 'user_logins');
        $distributeToDays($rawWhatsappCalls, 'whatsapp');
        $distributeToDays($rawPhoneCalls, 'phone');
        $distributeToDays($rawMails, 'mails');

        // ---------------------------------------------------------
        // GENEL TOPLAMLAR (ALL TIME - SIFIRLANMAZ)
        // ---------------------------------------------------------
        $totalUsersAllTime = NumberUsers::distinct('ip')->count('ip');
        $totalWhatsappAllTime = AramaKayit::where('tur', 'Whatsapp')->count();
        $totalPhoneAllTime = AramaKayit::where('tur', 'Telefon')->count();
        $totalMailsAllTime = 0;
        foreach ($formModels as $model) {
            $totalMailsAllTime += $model::count();
        }

        // ---------------------------------------------------------
        // TABLO VERİSİNİ OLUŞTURMA
        // ---------------------------------------------------------

        // Blade'de foreach dönebilmek için standart bir hafta oluşturuyoruz.
        // Ama içindeki verileri yukarıda hazırladığımız $cumulativeDays dizisinden çekiyoruz.
        $startOfSampleWeek = Carbon::now()->startOfWeek();
        $endOfSampleWeek = Carbon::now()->endOfWeek();
        $period = CarbonPeriod::create($startOfSampleWeek, $endOfSampleWeek);

        $days_data = [];

        foreach ($period as $date) {
            $dayIndex = $date->dayOfWeekIso; // 1..7

            $days_data[] = [
                // Tarih yazmıyoruz, sadece gün ismi (Çünkü ayın tüm o günlerini kapsıyor)
                'date_formatted' => $date->translatedFormat('l'), // Örn: Pazartesi
                'day_name' => $date->translatedFormat('l'),

                // Kümülatif diziden veriyi çek:
                'user_logins' => $cumulativeDays[$dayIndex]['user_logins'],
                'whatsapp' => $cumulativeDays[$dayIndex]['whatsapp'],
                'phone' => $cumulativeDays[$dayIndex]['phone'],
                'mails' => $cumulativeDays[$dayIndex]['mails'],
            ];
        }

        $totals = [
            'total_logins' => $totalUsersAllTime,
            'total_whatsapp' => $totalWhatsappAllTime,
            'total_phone' => $totalPhoneAllTime,
            'total_mails' => $totalMailsAllTime
        ];

        return [
            'week_start' => $startDate->translatedFormat('d F'), // Ayın başı
            'week_end' => $endDate->translatedFormat('d F'),   // Ayın sonu
            'days_data' => $days_data,
            'totals' => $totals,
        ];
    }


    private static function getAramaSayilari($tur)
    {
        return [
            'son_3_gun' => AramaKayit::where('tur', $tur)->where('updated_at', '>=', Carbon::now()->subDays(3))->count(),
            'son_7_gun' => AramaKayit::where('tur', $tur)->where('updated_at', '>=', Carbon::now()->subDays(7))->count(),
            'son_1_ay' => AramaKayit::where('tur', $tur)->where('updated_at', '>=', Carbon::now()->subMonth())->count(),
            'son_3_ay' => AramaKayit::where('tur', $tur)->where('updated_at', '>=', Carbon::now()->subMonths(3))->count(),
            'son_6_ay' => AramaKayit::where('tur', $tur)->where('updated_at', '>=', Carbon::now()->subMonths(6))->count(),
            'son_9_ay' => AramaKayit::where('tur', $tur)->where('updated_at', '>=', Carbon::now()->subMonths(9))->count(),
            'son_12_ay' => AramaKayit::where('tur', $tur)->where('updated_at', '>=', Carbon::now()->subMonths(12))->count(),
            'tum_whatsapp_aramalari' => AramaKayit::where('tur', $tur)->count(),
        ];
    }

    public function slugMaker(Request $request): JsonResponse
    {
        $slug = $this->commonService->slugMaker($request->text);
        return response()->json(['slug' => $slug]);
    }
}
