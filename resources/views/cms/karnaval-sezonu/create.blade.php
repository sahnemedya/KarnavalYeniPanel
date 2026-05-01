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
            <div class="card-header">Karnaval Sezonu Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.karnaval-sezonu.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="hit">Sıralaması:</label>
                    <input type="number" name="hit" id="hit" placeholder="Sayfa Sıralaması">

                    <label for="karnaval_yili" class="form-label font-weight-bold">Karnaval Sezonu:</label>
                    <select name="karnaval_yili" id="karnaval_yili" class="form-control">
                        @for ($yil = now()->year; $yil >= 2000; $yil--)
                            <option value="{{ $yil }}" {{ old('karnaval_yili', now()->year) == $yil ? 'selected' : '' }}>
                                {{ $yil }}
                            </option>
                        @endfor
                    </select>

                    <div class="form-group mb-3">
                        <label for="sezon_baslangici_gorsel" class="form-label font-weight-bold">Sezon Başlangıcı</label>

                        <input type="text" id="sezon_baslangici_gorsel" class="form-control" placeholder="Tarih seçin...">

                        <input type="hidden" name="sezon_baslangici" id="sezon_baslangici_gercek">
                    </div>

                    <div class="form-group mb-3">
                        <label for="karnaval_tarihi_baslangic_gorsel" class="form-label font-weight-bold">Karnaval Başlangıç Tarihi</label>
                        <input type="text" id="karnaval_tarihi_baslangic_gorsel" class="form-control" placeholder="Başlangıç seçin...">
                        <input type="hidden" name="karnaval_tarihi_baslangic" id="karnaval_tarihi_baslangic_gercek">
                    </div>

                    <div class="form-group mb-3">
                        <label for="karnaval_tarihi_bitis_gorsel" class="form-label font-weight-bold">Karnaval Bitiş Tarihi</label>
                        <input type="text" id="karnaval_tarihi_bitis_gorsel" class="form-control" placeholder="Bitiş seçin...">
                        <input type="hidden" name="karnaval_tarihi_bitis" id="karnaval_tarihi_bitis_gercek">
                    </div>

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
    <script src="{{asset("plugins/ckeditor/config.js")}}"></script>
    <script>
        let ckeditor = document.getElementById("contentText");
        if (ckeditor && typeof CKEDITOR !== "undefined") {
            CKEDITOR.replace('contentText', {
                filebrowserWindowWidth: '1000',
                filebrowserWindowHeight: '700'
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.0/air-datepicker.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. ADIM: Türkçe dil sözlüğümüzü tamamen manuel olarak oluşturuyoruz.
            // Dışarıya bağımlı olmadığımız için çalışmama ihtimali ortadan kalkıyor.
            const turkceDil = {
                days: ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'],
                daysShort: ['Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt'],
                daysMin: ['Pz', 'Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct'],
                months: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
                monthsShort: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'],
                today: 'Bugün',
                clear: 'Temizle',
                dateFormat: 'dd MMMM yyyy', // Ekranda görünecek format (Örn: 05 Mart 2026)
                firstDay: 1 // Takvimin ilk gününü Pazartesi (1) yapıyoruz
            };

            // 2. ADIM: Sezon Başlangıcı takvimini başlatıyoruz.
            new AirDatepicker('#sezon_baslangici_gorsel', {
                locale: turkceDil, // Kendi oluşturduğumuz Türkçe objesini veriyoruz.
                altField: '#sezon_baslangici_gercek', // Seçilen tarihi gizli input'a aktar.
                altFieldDateFormat: 'yyyy-MM-dd', // Gizli input'a yazarken Laravel'in formatına (Y-m-d) çevir.
                autoClose: true // Tarih seçilince takvim penceresini otomatik kapat.
            });

            // 3. ADIM: Karnaval Başlangıç ve Bitiş takvimlerini birbirine bağlıyoruz.

            // Önce Bitiş takvimini oluşturuyoruz ki, Başlangıç seçildiğinde buna müdahale edebilelim.
            let bitisTakvimi = new AirDatepicker('#karnaval_tarihi_bitis_gorsel', {
                locale: turkceDil,
                altField: '#karnaval_tarihi_bitis_gercek',
                altFieldDateFormat: 'yyyy-MM-dd',
                autoClose: true
            });

            // Başlangıç takvimini oluşturuyoruz.
            new AirDatepicker('#karnaval_tarihi_baslangic_gorsel', {
                locale: turkceDil,
                altField: '#karnaval_tarihi_baslangic_gercek',
                altFieldDateFormat: 'yyyy-MM-dd',
                autoClose: true,

                // onSelect: Kullanıcı bir tarih seçtiğinde tetiklenen olaydır.
                onSelect: function(secilen) {
                    // Seçilen tarihi alıp, Bitiş takviminin "seçilebilecek en erken tarih (minDate)" özelliğine atıyoruz.
                    // Böylece başlangıçtan önceki bir günü bitiş olarak seçmesini engelliyoruz.
                    if (secilen.date) {
                        bitisTakvimi.update({
                            minDate: secilen.date
                        });
                    }
                }
            });

        });
    </script>

@endsection
