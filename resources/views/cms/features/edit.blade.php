@extends("cms.partial.layout")

@section("extraCss")
    {{-- CKEditor --}}
    <link rel="stylesheet" href="{{asset('plugins/ckeditor/skins/moono/editor.css')}}">
    <script src="{{asset('plugins/ckeditor/lang/tr.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/styles.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

    {{-- LOKAL SELECT2 CSS --}}
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

    <style>
        /* Kutu ve Select2 Tasarımı */
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
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-8">
            <div class="card-header">Özellik Düzenle: <span class="text-warning">{{ $feature->name }}</span></div>
            <div class="card-body">
                <form action="{{ route('cms.features.update', $feature->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Bağlı Olduğu Sayfa (Select2 Aramalı) --}}
                    <label for="page">Bağlı Olduğu Sayfa <span class="text-danger">*</span></label>
                    <select name="page_id" id="page" required class="mb-4 form-control select2" style="width: 100%;">
                        {{-- Placeholder'ın kusursuz çalışması için value boş olmalı --}}
                        <option value=""></option>
                        @foreach($pages as $pageItem)
                            <option value="{{ $pageItem->id }}" {{ $feature->page_id == $pageItem->id ? 'selected' : '' }}>
                                {{ $pageItem->title }}
                            </option>
                        @endforeach
                    </select>
                    <br><br><br>
                    {{-- Kutu Tasarımı (Create sayfasıyla birebir uyumlu) --}}
                    <div class="question-row" style="padding: 20px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 10px; margin-bottom: 30px;">

                        <label>Başlık Giriniz <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ $feature->name }}" required>

                        <label>Gün Giriniz (Opsiyonel)</label>
                        <input type="text" name="gun" value="{{ $feature->gun }}">

                        {{-- Resim Düzenleme Alanı --}}
                        <div class="custom-media-grid grid-ortali mb-4 mt-3">
                            <div class="media-grid-item">
                                <figure class="media-card">
                                    <h3>Resim (Opsiyonel)</h3>
                                    <div class="media-preview">
                                        <a href="{{ $feature->image() }}" data-fancybox="gallery" class="media-target" style="display: {{ $feature->image ? 'flex' : 'none' }};">
                                            <img src="{{ $feature->image() }}" alt="Resim">
                                        </a>
                                        <img src="{{ asset('images/panel/site/default-placeholder.png') }}" class="default-media-img" alt="Silindi" style="display: {{ $feature->image ? 'none' : 'block' }};">
                                    </div>
                                    <input type="file" name="image" class="form-control mt-2">
                                    <button type="button" class="btn delete-image-btn bg-error mt-2" onclick="toggleMedia(this)" style="display: {{ $feature->image ? 'inline-block' : 'none' }}; width: 100%;">Mevcut Resmi Sil</button>
                                    <input type="checkbox" name="remove_image" class="d-none remove-checkbox">
                                </figure>
                            </div>
                        </div>

                        <label>Link Giriniz (Opsiyonel)</label>
                        <input type="text" name="url" value="{{ $feature->url }}">

                        <label>Gösterim Sırası</label>
                        <input type="number" name="hit" value="{{ $feature->hit }}">

                        <label>İçerik (Opsiyonel)</label>
                        <textarea name="content_text" id="contentText" cols="30" rows="10">{{ $feature->content }}</textarea>

                    </div>

                    <button type="submit" class="btn bg-primary w-100">Güncelle</button>
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

    {{-- VITE ENGELİNİ AŞMAK İÇİN JQUERY'İ ÇAĞIRIYORUZ --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- LOKAL SELECT2 JS --}}
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            // Select2 Başlatma
            $('.select2').select2({
                placeholder: "Yazarak arayın veya listeden seçin...",
                allowClear: true,
                width: '100%'
            });

            // CKEditor Başlatma
            let ckeditor = document.getElementById("contentText");
            if (ckeditor && typeof CKEDITOR !== "undefined") {
                CKEDITOR.replace('contentText', {
                    filebrowserWindowWidth: '1000',
                    filebrowserWindowHeight: '700'
                });
            }
        });

        // Resim Sil/Geri Yükle Fonksiyonu
        function toggleMedia(buttonElement) {
            const card = buttonElement.closest('.media-card');
            const mediaTarget = card.querySelector('.media-target');
            const defaultImg = card.querySelector('.default-media-img');
            const removeCheckbox = card.querySelector('.remove-checkbox');

            if (mediaTarget.style.display === "none") {
                mediaTarget.style.display = "flex";
                if (defaultImg) defaultImg.style.display = "none";
                buttonElement.classList.replace("bg-success", "bg-error");
                buttonElement.innerHTML = "Mevcut Resmi Sil";
                removeCheckbox.checked = false;
            } else {
                mediaTarget.style.display = "none";
                if (defaultImg) defaultImg.style.display = "block";
                buttonElement.classList.replace("bg-error", "bg-success");
                buttonElement.innerHTML = "Geri Yükle";
                removeCheckbox.checked = true;
            }
        }
    </script>
@endsection
