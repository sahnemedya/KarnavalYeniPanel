@extends("cms.partial.layout")
@section("extraCss")
    <link rel="stylesheet" href="{{asset('plugins/ckeditor/skins/moono/editor.css')}}">
    <script src="{{asset('plugins/ckeditor/lang/tr.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/styles.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
@endsection
@section("content")

    {{-- DİNAMİK SEÇİM İÇİN ARKA PLANDA MASTER ID'LERİ HESAPLIYORUZ --}}
    @php
        // Kaynak sayfanın bağlı olduğu ana kategori ID'si
        $masterCatId = null;
        if($sourcePage->category_id) {
            $sourceCat = \App\Models\Category::find($sourcePage->category_id);
            $masterCatId = $sourceCat ? ($sourceCat->translation_of ?? $sourceCat->id) : null;
        }

        // Kaynak sayfanın bağlı olduğu ana üst sayfa ID'si
        $masterParentId = null;
        if($sourcePage->parent_page) {
            $sourceParent = \App\Models\Page::find($sourcePage->parent_page);
            $masterParentId = $sourceParent ? ($sourceParent->translation_of ?? $sourceParent->id) : null;
        }
    @endphp

    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-12">
            <div class="card-header">
                <span class="text-warning">{{$sourcePage->title}}</span> Sayfasına Çeviri Ekle
            </div>



            <div class="card-body">
                <form action="{{route('cms.side-menu-elements.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header row gx-0">
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="langId" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa Dili <span class="text-danger">*</span></label>
                            <select name="lang_id" id="langId" required>
                                <option value="">Dil Seçiniz</option>
                                @foreach($languages as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label class="form-label" style="display: block; margin-bottom: 8px;"> Gemini ile İçeriği Doldur</label>
                            <button type="button" id="btnTranslateGemini" class="gemini-btn btn-info text-white">
                                <i class="las la-language"></i> Çevir
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="translation_of" value="{{ $sourcePage->id }}">

                    <div class="custom-media-grid mb-4">
                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>Ana Resim</h3>
                                <p>Kaynak sayfanın resmi. Değiştirmek isterseniz aşağıdan seçin.</p>
                                <div class="media-preview">
                                    <a href="{{ $sourcePage->image() }}" data-fancybox="gallery" class="media-target" style="display: {{ $sourcePage->image ? 'flex' : 'none' }};">
                                        <img src="{{ $sourcePage->image() }}" alt="Resim 1">
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}" class="default-media-img" alt="Silindi" style="display: {{ $sourcePage->image ? 'none' : 'block' }};">
                                </div>
                                <input type="file" name="image" id="image" class="form-control" placeholder="Resim Seçin">
                                <button type="button" class="btn delete-image-btn bg-error" onclick="toggleMedia(this)" style="display: {{ $sourcePage->image ? 'inline-block' : 'none' }};">Sil</button>
                                <input type="checkbox" name="remove_image" class="d-none remove-checkbox">
                            </figure>
                        </div>

                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>2. Resim/Icon</h3>
                                <p>Kaynak sayfanın ikinci resmi. Değiştirmek isterseniz aşağıdan seçin.</p>
                                <div class="media-preview">
                                    <a href="{{ $sourcePage->icon() }}" data-fancybox="gallery" class="media-target" style="display: {{ $sourcePage->icon ? 'flex' : 'none' }};">
                                        <img src="{{ $sourcePage->icon() }}" alt="Resim 2">
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}" class="default-media-img" alt="Silindi" style="display: {{ $sourcePage->icon ? 'none' : 'block' }};">
                                </div>
                                <input type="file" name="icon" id="icon" class="form-control" placeholder="İcon Seçin">
                                <button type="button" class="btn delete-image-btn bg-error" onclick="toggleMedia(this)" style="display: {{ $sourcePage->icon ? 'inline-block' : 'none' }};">Sil</button>
                                <input type="checkbox" name="remove_icon" class="d-none remove-checkbox">
                            </figure>
                        </div>
                    </div>

                    <div class="row gx-0">
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="sezonId" class="form-label" style="display: block; margin-bottom: 8px;">Karnaval Sezonu:</label>
                            <select name="sezon_id" id="sezonId">
                                <option value="">Tüm Sezonlar</option>
                                @if(isset($karnavalSezonlari))
                                    @foreach($karnavalSezonlari as $item)
                                        <option value="{{$item->id}}">{{$item->karnaval_yili}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="hit" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa Sıralaması:</label>
                            <input type="number" name="hit" id="hit" placeholder="Sayfa Sıralaması">
                        </div>
                    </div>

                    <div class="row gx-0">
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="title" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa Başlığı:</label>
                            <input type="text" name="title" id="title" placeholder="Başlık">
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="inside_title" class="form-label" style="display: block; margin-bottom: 8px;">İç Sayfa Başlığı:</label>
                            <input type="text" name="inside_title" id="inside_title" placeholder="İç Sayfa Başlığı">
                        </div>
                    </div>

                    <label for="slug">Sayfa Url</label>
                    <input type="text" name="slug" id="slug" placeholder="Url">

                    <label for="contentText">İçerik</label>
                    <textarea name="content_text" id="contentText" cols="30" rows="10"></textarea>

                    <br>

                    {{-- DİNAMİK ALANLAR --}}
                    <label for="categoryId">Bağlı Kategori (Dile Göre Otomatik Seçilir)</label>
                    <select name="category_id" id="categoryId" required>
                        <option value="">Önce Dil Seçiniz</option>
                    </select>

                    <label for="parentPage">Üst Sayfası (Dile Göre Otomatik Seçilir)</label>
                    <select name="parent_page" id="parentPage">
                        <option value="">Üst Sayfa Yok</option>
                    </select>

                    <label for="bladeId">Sayfa Şablonu</label>
                    <select name="blade_id" id="bladeId">
                        @foreach($blades as $item)
                            <option value="{{ $item->id }}" {{ $sourcePage->blade_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>

                    <br><br>
                    <input type="submit" value="Kaydet" class="btn bg-primary">
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section("extraJs")
    <script src="{{asset('plugins/ckeditor/config.js')}}"></script>
    <script>
        // EKSİK OLAN TOGGLE MEDIA EKLENDİ
        function toggleMedia(buttonElement) {
            const card = buttonElement.closest('.media-card');
            const mediaTarget = card.querySelector('.media-target');
            const defaultImg = card.querySelector('.default-media-img');
            const removeCheckbox = card.querySelector('.remove-checkbox');

            if (mediaTarget.style.display === "none") {
                mediaTarget.style.display = "flex";
                if (defaultImg) defaultImg.style.display = "none";
                buttonElement.classList.replace("bg-success", "bg-error");
                buttonElement.innerHTML = "Sil";
                removeCheckbox.checked = false;
            } else {
                mediaTarget.style.display = "none";
                if (defaultImg) defaultImg.style.display = "block";
                buttonElement.classList.replace("bg-error", "bg-success");
                buttonElement.innerHTML = "Geri Yükle";
                removeCheckbox.checked = true;
            }
        }

        // CKEditor
        let ckeditor = document.getElementById("contentText");
        if (ckeditor && typeof CKEDITOR !== "undefined") {
            CKEDITOR.replace('contentText', {
                filebrowserWindowWidth: '1000',
                filebrowserWindowHeight: '700'
            });
        }

        // Slug
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function () {
                    const title = this.value;
                    if (title.length > 0) {
                        axios.post('{{ route("cms.slug.maker") }}', {text: title}, {
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        }).then(function (response) {
                            slugInput.value = response.data.slug;
                        }).catch(function (error) {
                            console.error("Slug hatası", error);
                        });
                    } else {
                        slugInput.value = '';
                    }
                });
            }
        });
    </script>

    {{-- DİNAMİK SELECT GÜNCELLEME SCRIPTI (OTOMATİK EŞLEŞTİRME EKLENDİ) --}}
    <script>
        document.getElementById('langId').addEventListener('change', function() {
            const langId = this.value;
            const categorySelect = document.getElementById('categoryId');
            const parentPageSelect = document.getElementById('parentPage');

            // PHP'den gelen Master (Orijinal Türkçe) ID'leri JS değişkenine alıyoruz
            const masterCatId = "{{ $masterCatId }}";
            const masterParentId = "{{ $masterParentId }}";

            if (!langId) {
                categorySelect.innerHTML = '<option value="">Önce Dil Seçiniz</option>';
                parentPageSelect.innerHTML = '<option value="">Üst Sayfa Yok</option>';
                return;
            }

            categorySelect.innerHTML = '<option value="">Yükleniyor...</option>';
            parentPageSelect.innerHTML = '<option value="">Yükleniyor...</option>';

            axios.post('{{ route("cms.get.data.by.lang") }}', {
                lang_id: langId
            }, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
                .then(response => {
                    const data = response.data;

                    if(data.status === 'success') {
                        // Kategorileri Doldur ve Otomatik Seç
                        let catOptions = '<option value="">Kategori Seçiniz</option>';
                        data.categories.forEach(cat => {
                            // Gelen kategorinin kendi ID'si veya Çeviri ID'si, bizim Orijinal Master ID'ye eşit mi?
                            let isSelected = (cat.id == masterCatId || cat.translation_of == masterCatId) ? 'selected' : '';
                            catOptions += `<option value="${cat.id}" ${isSelected}>${cat.name}</option>`;
                        });
                        categorySelect.innerHTML = catOptions;

                        // Üst Sayfaları Doldur ve Otomatik Seç
                        let pageOptions = '<option value="">Üst Sayfa Yok</option>';
                        data.pages.forEach(page => {
                            let isSelected = (page.id == masterParentId || page.translation_of == masterParentId) ? 'selected' : '';
                            pageOptions += `<option value="${page.id}" ${isSelected}>${page.title}</option>`;
                        });
                        parentPageSelect.innerHTML = pageOptions;
                    } else {
                        notyf.error("Veri çekilemedi: " + data.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                    notyf.error("Bir hata oluştu.");
                });
        });
    </script>

    {{-- GEMINI ÇEVİRİ SCRİPTİ --}}
    <script>
        document.getElementById('btnTranslateGemini').addEventListener('click', function() {
            const btn = this;
            const langSelect = document.getElementById('langId');
            const langId = langSelect.value;
            const sourcePageId = document.querySelector('input[name="translation_of"]').value;

            if (!langId) {
                alert("Lütfen önce çeviri yapılacak dili seçiniz.");
                return;
            }

            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="las la-spinner la-spin"></i> Çevriliyor...';

            axios.post('{{ route("cms.fetch.translation") }}', {
                source_page_id: sourcePageId,
                target_lang_id: langId
            }, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
                .then(response => {
                    if (response.data.status === 'success') {
                        const data = response.data.data;

                        const titleVal = data.title || "";
                        document.getElementById('title').value = titleVal;

                        const insideTitleInput = document.getElementById('inside_title');
                        if(insideTitleInput) insideTitleInput.value = titleVal;

                        const titleInput = document.getElementById('title');
                        titleInput.dispatchEvent(new Event('input'));

                        const contentVal = data.content || "";
                        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['contentText']) {
                            CKEDITOR.instances['contentText'].setData(contentVal);
                        } else {
                            document.getElementById('contentText').value = contentVal;
                        }

                        if(typeof notyf !== 'undefined') notyf.success('Çeviri tamamlandı.');
                    } else {
                        alert("Hata: " + (response.data.message || 'Bilinmeyen hata'));
                    }
                })
                .catch(error => {
                    console.error("Gemini Hatası:", error);
                    alert("Bir hata oluştu. Konsolu kontrol edin.");
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });
    </script>
@endsection
