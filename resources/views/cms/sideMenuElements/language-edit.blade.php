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

            <div class="alert alert-info border-0 mb-0" style="border-radius: 0;">
                <i class="las la-info-circle"></i>
                Şu an <strong>{{ $sourcePage->title ?? 'Bilinmeyen Sayfa' }}</strong> sayfasının
                <strong>{{ $page->language->name ?? '' }}</strong> çevirisini düzenliyorsunuz.
            </div>

            <div class="card-header">
                <span class="text-warning">{{ $page->title }}</span> Sayfasını Güncelle
            </div>
            <div class="card-header">
                <button type="button" id="btnTranslateGeminiUpdate" class="btn btn-warning text-white">
                    <i class="las la-sync"></i> Kaynak Sayfadan Yeniden Çevir
                </button>
            </div>
            <div class="card-body">
                <form action="{{route("cms.side-menu-elements.update",$page->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- 1. SAYFA DİLİ --}}
                    <label for="langId">Sayfa Dili</label>
                    <select name="lang_id" id="langId">
                        @foreach($languages as $item)
                            <option value="{{$item->id}}"
                                {{ $page->lang_id == $item->id ? 'selected' : '' }}>
                                {{$item->name}}
                            </option>
                        @endforeach
                    </select>

                    <label for="title">Sayfa Başlığı:</label>
                    <input type="text" name="title" id="title" placeholder="Başlık" value="{{$page->title}}">

                    <label for="inside_title">İç Sayfa Başlığı:</label>
                    <input type="text" name="inside_title" id="inside_title" placeholder="İç Sayfa Başlık (H1)" value="{{$page->inside_title}}">

                    <label for="slug">Sayfa Url</label>
                    <input type="text" name="slug" id="slug" placeholder="Url" value="{{$page->slug}}">

                    <label for="contentText">İçerik</label>
                    <textarea name="content_text" id="contentText" cols="30" rows="10">{{$page->content}}</textarea>
                    <br>
                    <label for="hit">Sayfa Sıralaması:</label>
                    <input type="number" name="hit" id="hit" placeholder="Sayfa Sıralaması" value="{{$page->hit}}">
                    <label for="image">Sayfa Resmi</label>
                    @if($page->image)
                        <figure class="form-figure">
                            <img src="{{$page->getImagePath()}}" id="formFigure" alt="">
                            <label onclick="removeImage()" class="btn delete-image-btn bg-error mt-2" id="removeButton">
                                Resmi Sil
                            </label>
                        </figure>
                    @endif
                    <input type="checkbox" style="display: none" name="removeImage" id="removeCheckbox" value="1">
                    <input type="file" name="image" id="image" class="mt-2" placeholder="Dosya Seçin">

                    <label for="categoryId">Bağlı Kategori</label>
                    <select name="category_id" id="categoryId">
                        @foreach($categories as $item)
                            <option value="{{$item->id}}"
                                {{ $page->category_id == $item->id ? 'selected' : '' }}>
                                {{$item->name}}
                            </option>
                        @endforeach
                    </select>
                    <label for="bladeId">Sayfa Şablonu</label>
                    <select name="blade_id" id="bladeId" title="Sayfanın görüntüleneceği tasarım şablonunu seçin.">
                        @foreach($blades as $item)
                            <option value="{{$item->id}}"
                                    @if($page->blade_id == $item->id) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>

                    <label for="parentPage">Üst Sayfası</label>
                    <select name="parent_page" id="parentPage">
                        <option value="" @if($page->parent_page==NULL) selected @endif>Üst Sayfa Yok</option>
                        @foreach($pages as $item)
                            @if($item->id == $page->id) @continue @endif
                            <option value="{{$item->id}}"
                                    @if($page->parent_page == $item->id) selected @endif>
                                {{$item->title}}
                            </option>
                        @endforeach
                    </select>

                    {{-- 8. HANGİ SAYFANIN ÇEVİRİSİ (GÜNCELLENDİ) --}}
                    <label for="translationOf">Hangi Sayfanın Çevirisi</label>
                    <select name="translation_of" id="translationOf">
                        <option value="">Çeviri Sayfası Değil</option>
                        {{-- Burası artık Sadece Türkçe Sayfaları Döndürür --}}
                        @foreach($sourcePagesList as $item)
                            <option value="{{$item->id}}"
                                    @if($page->translation_of == $item->id) selected @endif>
                                {{$item->title}}
                            </option>
                        @endforeach
                    </select>

                    <input type="submit" value="Güncelle">

                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
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
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        }).then(function (response) {
                            slugInput.value = response.data.slug;
                        }).catch(function (error) { console.error("Slug hatası", error); });
                    } else {
                        slugInput.value = '';
                    }
                });
            }
        });

        function removeImage() {
            let categoryImage = document.getElementById('formFigure');
            const removeButton = document.getElementById("removeButton");
            const removeCheckbox = document.getElementById("removeCheckbox")
            if (categoryImage.style.display === "none") {
                categoryImage.style.display = "block";
                removeButton.classList.remove("bg-success");
                removeButton.classList.add("bg-error");
                removeButton.innerHTML = "Resmi Sil";
                removeCheckbox.removeAttribute("checked");
            } else {
                categoryImage.style.display = "none";
                removeButton.classList.add("bg-success");
                removeButton.classList.remove("bg-error");
                removeButton.innerHTML = "Resmi Geri Yükle";
                removeCheckbox.setAttribute("checked", "checked");
            }
        }
    </script>
    <script>
        document.getElementById('btnTranslateGeminiUpdate').addEventListener('click', function() {
            // Edit sayfasında translation_of selectbox'ından veya hidden inputtan değeri alıyoruz
            // Senin edit sayfasında translation_of bir SELECT olarak duruyor.
            const sourcePageSelect = document.getElementById('translationOf');
            const sourcePageId = sourcePageSelect.value;
            const langId = document.getElementById('langId').value;
            const btn = this;

            if (!sourcePageId) {
                alert("Bu sayfa bir çeviri sayfası değil veya kaynak seçili değil.");
                return;
            }

            if(!confirm('Mevcut başlık ve içerik, kaynak sayfanın güncel çevirisi ile DEĞİŞTİRİLECEK. Onaylıyor musunuz?')) return;

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

                        document.getElementById('title').value = data.title;

                        // CKEditor Güncelle
                        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['contentText']) {
                            CKEDITOR.instances['contentText'].setData(data.content);
                        }

                        if(typeof notyf !== 'undefined') notyf.success('Çeviri güncellendi.');
                    } else {
                        alert("Hata oluştu.");
                    }
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="las la-sync"></i> Kaynak Sayfadan Yeniden Çevir';
                });
        });
    </script>
@endsection
