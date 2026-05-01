@extends("cms.partial.layout")

@section("extraCss")
    {{-- CKEditor Dosyaları --}}
    <link rel="stylesheet" href="{{asset('plugins/ckeditor/skins/moono/editor.css')}}">
    <script src="{{asset('plugins/ckeditor/lang/tr.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/styles.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

    {{-- Lokal Select2 CSS --}}
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">


    <style>
        .question-row { padding: 20px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; margin-bottom: 30px; }
        .select2-container--default .select2-selection--single { background-color: #11141a !important; border: 1px solid #2b2b40 !important; border-radius: 6px !important; height: 42px !important; display: block !important; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { color: #ffffff !important; line-height: 40px !important; padding-left: 15px !important; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; top: 0 !important; right: 10px !important; }
        .select2-dropdown { background-color: #1a1d24 !important; border: 1px solid #2b2b40 !important; }
        .select2-search--dropdown .select2-search__field { background-color: #11141a !important; color: #ffffff !important; border: 1px solid #3b3b54 !important; padding: 8px !important; }
        .select2-results__option { color: #a1a5b7 !important; }
        .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #2b2b40 !important; color: #ffffff !important; }
    </style>
@endsection

@section("content")
    {{-- Arka planda tüm sayfaların mevcut özellik sayılarını tek bir sorguyla alıyoruz --}}
    @php
        $pageCounts = \App\Models\Feature::selectRaw('page_id, count(*) as count')
                        ->groupBy('page_id')
                        ->pluck('count', 'page_id')
                        ->toArray();
    @endphp

    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-8">
            <div class="card-header">Sayfa Özellikleri Ekle (Çoklu)</div>
            <div class="card-body">
                <form action="{{ route('cms.features.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Bağlı Olduğu Sayfa --}}
                    <label for="page">Bağlı Olduğu Sayfa <span class="text-danger">*</span></label>
                    <select name="page_id" id="page" required class="mb-4 form-control select2" style="width: 100%;">
                        <option value=""></option>
                        @foreach($pages as $pageItem)
                            <option value="{{ $pageItem->id }}"
                                {{ (isset($selectedPage) && $selectedPage->id == $pageItem->id) || request('page_id') == $pageItem->id ? 'selected' : '' }}>
                                {{ $pageItem->title }}
                            </option>
                        @endforeach
                    </select>
                    <br><br><br>
                    {{-- Dinamik Olarak Eklenecek Alan (Başlangıçta Boş) --}}
                    <div id="features_wrapper"></div>

                    {{-- İşlem Butonları (Başlangıçta Gizli, Sayfa Seçilince Açılacak) --}}
                    <div id="action_buttons" style="display: none;">
                        <div class="text-end mb-4" style="text-align: right;">
                            <button type="button" class="btn bg-success text-white" onclick="addFeatures()">+ Yeni Özellik Ekle</button>
                        </div>
                        <button type="submit" class="btn bg-primary w-100">Tümünü Kaydet</button>
                    </div>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section("extraJs")
    <script src="{{asset('plugins/ckeditor/config.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>

    <script>
        // PHP'den gelen sayfa özellik sayılarını JavaScript objesine aktarıyoruz
        const featureCounts = @json($pageCounts);
        let currentDbCount = 0;

        $(document).ready(function() {
            // Select2 Başlatma
            $('.select2').select2({
                placeholder: "Yazarak arayın veya listeden seçin...",
                allowClear: true,
                width: '100%'
            });

            // Seçim Elemanları
            let $pageSelect = $('#page');
            let $actionButtons = $('#action_buttons');
            let $wrapper = $('#features_wrapper');

            // Formun Görünürlüğünü ve Sayılarını Ayarlayan Ana Fonksiyon
            function updateFormState(pageId, isInitialLoad = false) {
                if (pageId) {
                    // Sayfaya ait kayıtlı özellik sayısını al (yoksa 0)
                    currentDbCount = featureCounts[pageId] || 0;

                    // Butonları göster
                    $actionButtons.show();

                    // Eğer formda hiç özellik kutusu yoksa, otomatik 1 tane ekle
                    if ($wrapper.children('.question-row').length === 0) {
                        addFeatures();
                    } else {
                        // Kutu varsa sayıları baştan hesapla
                        reorderQuestions();
                    }
                } else {
                    // Sayfa seçilmediyse her şeyi gizle ve temizle
                    $actionButtons.hide();
                    $wrapper.empty();
                }
            }

            // Sayfa seçimi değiştiğinde tetiklenir
            $pageSelect.on('change', function() {
                // Yeni bir sayfa seçildiğinde eski yazılanları temizle ki karışmasın
                $wrapper.empty();
                updateFormState($(this).val(), false);
            });

            // Sayfa ilk yüklendiğinde (Parametreyle gelmişse) kontrol et
            let initialPageId = $pageSelect.val();
            updateFormState(initialPageId, true);
        });

        // Dinamik Yeni Kutu Ekleme Fonksiyonu
        function addFeatures() {
            let wrapper = document.getElementById("features_wrapper");

            let html = `
                <div class="question-row" style="padding: 20px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 class="question-title" style="color: #3b82f6; margin: 0;">... Özellik</h3>
                        <button type="button" class="btn bg-error btn-sm" onclick="removeQuestion(this)">✖ Sil</button>
                    </div>

                    <label>Başlık Giriniz <span class="text-danger">*</span></label>
                    <input type="text" name="name[]" placeholder="Başlık Giriniz" required>

                    <label>Gün Giriniz (Opsiyonel)</label>
                    <input type="text" name="gun[]" placeholder="Örn: 2. Gün">

                    <label>Resim (Opsiyonel)</label>
                    <input type="file" name="image[]" class="form-control" style="margin-bottom: 10px;">

                    <label>Link Giriniz (Opsiyonel)</label>
                    <input type="text" name="url[]" placeholder="Yönlendirme Linki">

                    <label>Gösterim Sırası</label>
                    <input type="number" name="hit[]" class="hit-input">

                    <label>İçerik (Opsiyonel)</label>
                    <textarea name="content_text[]" class="ckeditor-area"></textarea>
                </div>
            `;
            wrapper.insertAdjacentHTML('beforeend', html);

            // Yeni eklenen CKEditor'leri başlat ve sayıları sırala
            initEditors();
            reorderQuestions();
        }

        // Kutu Silme Fonksiyonu
        function removeQuestion(btn) {
            let row = btn.closest('.question-row');
            let textarea = row.querySelector('textarea');

            // Silinen kutuya ait CKEditor örneğini hafızadan temizle
            if(textarea && textarea.id && typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[textarea.id]) {
                CKEDITOR.instances[textarea.id].destroy();
            }

            row.remove();

            // Kutu silindiği için sayıları baştan hesapla
            reorderQuestions();
        }

        // Başlıkları ve Hit (10, 20, 30...) Değerlerini Hesaplama Fonksiyonu
        function reorderQuestions() {
            let rows = document.querySelectorAll('.question-row');

            rows.forEach((row, index) => {
                // Formül: (Veritabanındaki Kayıt Sayısı) + (Ekranda Bulunduğu Sıra) + 1
                let currentNum = currentDbCount + index + 1;

                // Başlığı Güncelle
                let title = row.querySelector('.question-title');
                if(title) title.innerText = currentNum + ". Özellik";

                // Hit Değerini Güncelle (Sıranın 10 Katı)
                let hitInput = row.querySelector('.hit-input');
                if(hitInput) hitInput.value = currentNum * 10;
            });
        }

        // CKEditor Başlatma Fonksiyonu
        function initEditors() {
            document.querySelectorAll('.ckeditor-area').forEach((el) => {
                if(!el.id) {
                    el.id = 'editor_' + Math.random().toString(36).substr(2, 9);
                }
                if (typeof CKEDITOR !== "undefined" && !CKEDITOR.instances[el.id]) {
                    CKEDITOR.replace(el.id, { height: '150px' });
                }
            });
        }
    </script>
@endsection
