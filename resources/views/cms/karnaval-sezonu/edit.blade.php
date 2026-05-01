@extends("cms.partial.layout")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.0/air-datepicker.min.css">
@section("extraCss")
    <link rel="stylesheet" href="{{asset("plugins/ckeditor/skins/moono/editor.css")}}">
    <script src="{{asset("plugins/ckeditor/lang/tr.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/styles.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/ckeditor.js")}}"></script>
@endsection
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Karnaval Sezonunu Düzenle</div>
            <div class="card-body">
                <form action="{{ route('cms.karnaval-sezonu.update', $karnavalSezonlari->id) }}" method="POST">

                    {{-- Laravel form güvenliği için CSRF token zorunludur --}}
                    @csrf

                    {{-- Formun aslında bir GÜNCELLEME (PUT) işlemi olduğunu Laravel'e bildiren sihirli kod --}}
                    @method('PUT')
                    <label for="hit">Sıralaması:</label>
                    <input type="number" name="hit" id="hit" placeholder="Sayfa Sıralaması" value="{{$page->hit}}">

                    {{-- 1. DİNAMİK YIL SEÇİCİ --}}
                    <div class="form-group mb-3">
                        <label for="karnaval_yili" class="form-label font-weight-bold">Karnaval Sezonu (Yıl):</label>
                        <select name="karnaval_yili" id="karnaval_yili" class="form-control">
                            {{-- 2000 yılına kadar geriye dönük listeleme döngümüz --}}
                            @for ($yil = now()->year + 1; $yil >= 2000; $yil--)
                                {{--
                                    old('karnaval_yili', $karnavalSezonlari->karnaval_yili) Mantığı:
                                    Önce formda hata var mı diye 'old' değerine bakar, yoksa veritabanından gelen mevcut yıla bakar.
                                    Eğer bu değer döngüdeki $yil'a eşitse, o option'ı 'selected' (seçili) yapar.
                                --}}
                                <option value="{{ $yil }}" {{ old('karnaval_yili', $karnavalSezonlari->karnaval_yili) == $yil ? 'selected' : '' }}>
                                    {{ $yil }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- 2. SEZON BAŞLANGICI (Air Datepicker) --}}
                    <div class="form-group mb-3">
                        <label for="sezon_baslangici_gorsel" class="form-label font-weight-bold">Sezon Başlangıcı</label>
                        {{-- Kullanıcının göreceği input. Değerini Javascript içinden dolduracağız, o yüzden boş bırakıyoruz. --}}
                        <input type="text" id="sezon_baslangici_gorsel" class="form-control" placeholder="Tarih seçin...">

                        {{-- Laravel'e (Backend) gidecek gerçek gizli input. Mevcut veriyi 'value' içine yazdırıyoruz. --}}
                        <input type="hidden" name="sezon_baslangici" id="sezon_baslangici_gercek" value="{{ old('sezon_baslangici', $karnavalSezonlari->sezon_baslangici) }}">
                    </div>

                    {{-- 3. KARNAVAL BAŞLANGIÇ TARİHİ (Air Datepicker) --}}
                    <div class="form-group mb-3">
                        <label for="karnaval_tarihi_baslangic_gorsel" class="form-label font-weight-bold">Karnaval Başlangıç Tarihi</label>
                        <input type="text" id="karnaval_tarihi_baslangic_gorsel" class="form-control" placeholder="Başlangıç seçin...">
                        <input type="hidden" name="karnaval_tarihi_baslangic" id="karnaval_tarihi_baslangic_gercek" value="{{ old('karnaval_tarihi_baslangic', $karnavalSezonlari->karnaval_tarihi_baslangic) }}">
                    </div>

                    {{-- 4. KARNAVAL BİTİŞ TARİHİ (Air Datepicker) --}}
                    <div class="form-group mb-3">
                        <label for="karnaval_tarihi_bitis_gorsel" class="form-label font-weight-bold">Karnaval Bitiş Tarihi</label>
                        <input type="text" id="karnaval_tarihi_bitis_gorsel" class="form-control" placeholder="Bitiş seçin...">
                        <input type="hidden" name="karnaval_tarihi_bitis" id="karnaval_tarihi_bitis_gercek" value="{{ old('karnaval_tarihi_bitis', $karnavalSezonlari->karnaval_tarihi_bitis) }}">
                    </div>


                    {{-- KAYDET BUTONU --}}
                    <input type="submit" value="Kaydet">


                </form>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
@section("extraJs")

    {{-- Air Datepicker CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.0/air-datepicker.min.css">

    {{-- Air Datepicker JS --}}
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.0/air-datepicker.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Türkçe dil ayarlarımız
            const turkceDil = {
                days: ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'],
                daysShort: ['Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt'],
                daysMin: ['Pz', 'Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct'],
                months: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
                monthsShort: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'],
                today: 'Bugün',
                clear: 'Temizle',
                dateFormat: 'dd MMMM yyyy',
                firstDay: 1
            };

            // Veritabanından gelen mevcut tarihleri JS değişkenlerine aktarıyoruz.
            // JS 'new Date()' fonksiyonunun anlayabilmesi için '2026-03-05' gibi bir format geliyor.
            const mevcutSezonBaslangici = '{{ old('sezon_baslangici', $karnavalSezonlari->sezon_baslangici) }}';
            const mevcutKarnavalBaslangic = '{{ old('karnaval_tarihi_baslangic', $karnavalSezonlari->karnaval_tarihi_baslangic) }}';
            const mevcutKarnavalBitis = '{{ old('karnaval_tarihi_bitis', $karnavalSezonlari->karnaval_tarihi_bitis) }}';

            // 1. Sezon Başlangıcı Takvimi
            new AirDatepicker('#sezon_baslangici_gorsel', {
                locale: turkceDil,
                altField: '#sezon_baslangici_gercek',
                altFieldDateFormat: 'yyyy-MM-dd',
                autoClose: true,
                // Eğer veritabanından bir tarih gelmişse, takvimi açar açmaz onu seçili (selected) hale getirir
                selectedDates: mevcutSezonBaslangici ? [new Date(mevcutSezonBaslangici)] : []
            });

            // 2. Karnaval Bitiş Takvimi (Önce bitişi tanımlıyoruz ki başlangıçta onSelect ile müdahale edebilelim)
            let bitisTakvimi = new AirDatepicker('#karnaval_tarihi_bitis_gorsel', {
                locale: turkceDil,
                altField: '#karnaval_tarihi_bitis_gercek',
                altFieldDateFormat: 'yyyy-MM-dd',
                autoClose: true,
                // Veritabanındaki bitiş tarihini seçili yap
                selectedDates: mevcutKarnavalBitis ? [new Date(mevcutKarnavalBitis)] : []
            });

            // 3. Karnaval Başlangıç Takvimi
            new AirDatepicker('#karnaval_tarihi_baslangic_gorsel', {
                locale: turkceDil,
                altField: '#karnaval_tarihi_baslangic_gercek',
                altFieldDateFormat: 'yyyy-MM-dd',
                autoClose: true,
                // Veritabanındaki başlangıç tarihini seçili yap
                selectedDates: mevcutKarnavalBaslangic ? [new Date(mevcutKarnavalBaslangic)] : [],

                // Eğer kullanıcı düzenleme ekranında başlangıç tarihini değiştirirse...
                onSelect: function(secilen) {
                    // Bitiş takviminin minDate ayarını yeni seçilen tarihe göre sınırla.
                    if (secilen.date) {
                        bitisTakvimi.update({ minDate: secilen.date });
                    }
                }
            });

            // Sayfa ilk yüklendiğinde, veritabanında zaten bir başlangıç tarihi varsa,
            // Bitiş takviminin "geçmişe dönük" kısıtlamasını o mevcut tarihe göre hemen kuralım.
            if (mevcutKarnavalBaslangic) {
                bitisTakvimi.update({ minDate: new Date(mevcutKarnavalBaslangic) });
            }
        });
    </script>

@endsection
