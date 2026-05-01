@extends("cms.partial.layout")
@section("extraCss")
    <link rel="stylesheet" href="{{asset("plugins/ckeditor/skins/moono/editor.css")}}">
    <script src="{{asset("plugins/ckeditor/lang/tr.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/styles.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/ckeditor.js")}}"></script>
@endsection
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-12">
            <div class="card-header"><span class="text-warning">{{$category->name}}</span> Alt Sayfası Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.side-menu-elements.store")}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row gx-0">
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="sezonId" class="form-label" style="display: block; margin-bottom: 8px;">Karnaval
                                Sezonu:</label>
                            <select name="sezon_id" id="sezonId" title="Hangi Karnaval Sezonuna ait ise o seçilmeli.">
                                <option value="">Tüm Sezonlar</option>
                                @foreach($karnavalSezonlari as $item)
                                    <option value="{{$item->id}}">{{$item->karnaval_yili}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="hit" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa
                                Sıralaması:</label>
                            <input type="number" name="hit" id="hit" placeholder="Sayfa Sıralaması">
                        </div>
                    </div>

                    <div class="row gx-0">
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">

                            <label for="title" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa
                                Başlığı:</label>
                            <input type="text" name="title" id="title" placeholder="Başlık">
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="inside_title" class="form-label" style="display: block; margin-bottom: 8px;">İç
                                Sayfa Başlığı:</label>
                            <input type="text" name="inside_title" id="inside_title" placeholder="İç Sayfa Başlığı">
                        </div>

                    </div>
                    <label for="slug">Sayfa Url</label>
                    <input type="text" name="slug" id="slug" placeholder="Url">

                    <label for="contentText">İçerik</label>
                    <textarea name="content_text" id="contentText" cols="30" rows="10"></textarea>

                    <div class="row gx-0 card-ic" style="margin-top: 20px">
                        <div class="card-header">Resim Alanı</div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="image" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa
                                Resmi</label>
                            <input class="form-control" type="file" name="image" id="image" style="width: 100%;"
                                   placeholder="Dosya Seçin">
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="icon" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa İconu
                                Değiştir</label>
                            <input class="form-control" type="file" name="icon" id="icon" style="width: 100%;"
                                   placeholder="Dosya Seçin">
                        </div>
                    </div>


                    <div class="row gx-0 card-ic" style="margin-top: 20px">
                        <div class="card-header">Kategori ve Sayfa İlişkileri</div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="categoryId" class="form-label" style="display: block; margin-bottom: 8px;">Bağlı Kategori</label>
                            <select name="category_id" id="categoryId" title="Hangi kategorinin sayfası ise o seçilmeli.">
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="parentPage" class="form-label" style="display: block; margin-bottom: 8px;">Üst Sayfası</label>
                            <select name="parent_page" id="parentPage">
                                <option value="">Üst Sayfa Yok</option>

                                @foreach($pages as $item)
                                    <option value="{{$item->id}}"
                                        {{$mainPage && $mainPage->id == $item->id ? 'selected' : ''}}>
                                        {{$item->title}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="bladeId" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa Şablonu</label>
                            <select name="blade_id" id="bladeId" title="Sayfanın görüntüleneceği tasarım şablonunu seçin.">
                                @foreach($blades as $item)
                                    <option value="{{ $item->id }}"
                                        {{
                                            ($blogDetailBlade && $blogDetailBlade->id == $item->id)
                                                ? 'selected'
                                                : ''
                                        }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">

                            @if($languages->count()>1)
                                <label for="translationOf" class="form-label" style="display: block; margin-bottom: 8px;">Hangi Sayfanın Çevirisi</label>
                                <select name="translationOf" id="translationOf">
                                    <option value="">Çeviri Sayfası Değil</option>
                                    @foreach($pages as $item)
                                        <option value="{{$item->id}}">{{$item->title}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>



                    @if($languages->count()>1)
                        <label for="langId">Sayfa Dili</label>
                        <select name="lang_id" id="langId">
                            @foreach($languages as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="lang_id" value="{{ $languages->first()->id }}">
                    @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function () {
                    const title = this.value;

                    if (title.length > 0) {
                        axios.post('{{ route("cms.slug.maker") }}', {text: title}, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(function (response) {
                            slugInput.value = response.data.slug;
                        }).catch(function (error) {
                            console.error("Slug oluşturulamadı", error);
                        });
                    } else {
                        slugInput.value = '';
                    }
                });
            } else {
                console.warn("#title veya #slug inputu bulunamadı.");
            }
        });
    </script>
    {{-- GEMINI ÇEVİRİ SCRİPTİ (DÜZELTİLDİ) --}}
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

            // DÜZELTME BURADA YAPILDI: "cms." EKLENDİ
            axios.post('{{ route("cms.fetch.translation") }}', {
                source_page_id: sourcePageId,
                target_lang_id: langId
            }, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
                .then(response => {
                    console.log("Gemini Yanıtı:", response.data);

                    if (response.data.status === 'success') {
                        const data = response.data.data;

                        // Başlık Doldurma
                        const titleVal = data.title || "";
                        document.getElementById('title').value = titleVal;

                        const insideTitleInput = document.getElementById('inside_title');
                        if(insideTitleInput) insideTitleInput.value = titleVal;

                        // Slug tetikleme
                        const titleInput = document.getElementById('title');
                        titleInput.dispatchEvent(new Event('input'));

                        // İçerik Doldurma
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
