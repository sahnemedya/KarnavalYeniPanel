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
            <div class="card-header">Sayfa Güncelle</div>
            <div class="card-body">
                <form action="{{route("cms.pages.update",$page->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="custom-media-grid mb-4">

                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>Ana Resim</h3>
                                <div class="media-preview">
                                    <a href="{{ $page->image() }}" data-fancybox="gallery" class="media-target"
                                       style="display: {{ $page->image ? 'flex' : 'none' }};">
                                        <img src="{{ $page->image() }}" alt="Resim 1">
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}"
                                         class="default-media-img" alt="Silindi"
                                         style="display: {{ $page->image ? 'none' : 'block' }};">
                                </div>
                                <input type="file" name="image" id="image" class="form-control"
                                       placeholder="Resim Seçin">
                                <button type="button" class="btn delete-image-btn bg-error" onclick="toggleMedia(this)"
                                        style="display: {{ $page->image ? 'inline-block' : 'none' }};">Sil
                                </button>
                                <input type="checkbox" name="remove_image" class="d-none remove-checkbox">
                            </figure>
                        </div>

                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>2. Resim/Icon</h3>
                                <div class="media-preview">
                                    <a href="{{ $page->icon() }}" data-fancybox="gallery">
                                        <img src="{{ $page->icon() }}" class="media-target" alt="Resim 2"
                                             style="display: {{ $page->icon ? 'flex' : 'none' }};">
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}"
                                         class="default-media-img" alt="Silindi"
                                         style="display: {{ $page->icon ? 'none' : 'block' }};">
                                </div>
                                <input type="file" name="icon" id="icon" class="form-control" placeholder="İcon Seçin">
                                <button type="button" class="btn delete-image-btn bg-error" onclick="toggleMedia(this)"
                                        style="display: {{ $page->icon ? 'inline-block' : 'none' }};">Sil
                                </button>
                                <input type="checkbox" name="remove_icon" class="d-none remove-checkbox">
                            </figure>
                        </div>

                    </div>

                    <div class="row gx-0">
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="sezonId" class="form-label" style="display: block; margin-bottom: 8px;">Karnaval
                                Sezonu:</label>
                            <select name="sezon_id" id="sezonId" title="Hangi Karnaval Sezonuna ait ise o seçilmeli.">
                                <option value="">Tüm Sezonlar</option>
                                @foreach($karnavalSezonlari as $item)
                                    <option value="{{$item->id}}" @if($page->sezon_id == $item->id) selected @endif>
                                        {{$item->karnaval_yili}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="hit" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa
                                Sıralaması:</label>
                            <input type="number" name="hit" id="hit" placeholder="Sayfa Sıralaması"
                                   value="{{$page->hit}}">
                        </div>
                    </div>

                    <div class="row gx-0">
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="title" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa Başlığı:</label>
                            <input type="text" name="title" id="title" placeholder="Başlık" value="{{$page->title}}">
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="inside_title" class="form-label" style="display: block; margin-bottom: 8px;">İç Sayfa Başlığı:</label>
                            <input type="text" name="inside_title" id="inside_title" placeholder="İç Sayfa Başlık (H1)" value="{{$page->inside_title}}">
                        </div>
                    </div>


                    <label for="slug">Sayfa Url</label>
                    <input type="text" name="slug" id="slug" placeholder="Url" value="{{$page->slug}}">

                    <label for="contentText">İçerik</label>
                    <textarea name="content_text" id="contentText" cols="30" rows="10">{{$page->content}}</textarea>
                    <br>


                    <div class="row gx-0 card-ic" style="margin-top: 20px">
                        <div class="card-header">Kategori ve Sayfa İlişkileri</div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="categoryId" class="form-label" style="display: block; margin-bottom: 8px;">Bağlı
                                Kategori</label>
                            <select name="category_id" id="categoryId"
                                    title="Hangi kategorinin sayfası ise o seçilmeli.">
                                @foreach($categories as $item)
                                    <option value="{{$item->id}}"
                                            @if($page->category_id == $item->id) selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="parentPage" class="form-label" style="display: block; margin-bottom: 8px;">Üst
                                Sayfası</label>
                            <select name="parent_page" id="parentPage">
                                <option value="" @if($page->parent_page==NULL) selected @endif>Üst Sayfa Yok</option>
                                @foreach($pages as $item)
                                    <option value="{{$item->id}}"
                                            @if($page->parent_page == $item->id) selected @endif>{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="bladeId" class="form-label" style="display: block; margin-bottom: 8px;">Sayfa
                                Şablonu</label>
                            <select name="blade_id" id="bladeId"
                                    title="Sayfanın görüntüleneceği tasarım şablonunu seçin.">
                                @foreach($blades as $item)
                                    <option value="{{$item->id}}" @if($page->blade_id == $item->id) selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            @if($languages->count()>1)
                                <label for="translationOf" class="form-label"
                                       style="display: block; margin-bottom: 8px;">Hangi
                                    Sayfanın Çevirisi</label>
                                <select name="translation_of" id="translationOf">
                                    <option value="">Çeviri Sayfası Değil</option>
                                    @foreach($pages as $item)
                                        <option value="{{$item->id}}"
                                                @if($page->translation_of == $item->id) selected @endif>{{$item->title}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    @if($languages->count()>1)
                        <label for="langId">Sayfa Dili</label>
                        <select name="lang_id" id="langId">
                            @foreach($languages as $item)
                                <option value="{{$item->id}}"
                                        @if($page->lang_id == $item->id) selected @endif>{{$item->name}}</option>
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
    <div id="customFileModal" class="custom-file-modal">
        <div class="modal-content-wrapper">
            <button class="close-modal-btn" onclick="closeFileModal()">✖</button>

            <iframe id="fileIframe" src="" frameborder="0" style="display: none;"></iframe>

            <div id="sesContainer" class="ses-modal-container" style="display: none;">
                <audio id="modalSesPlayer" controls class="w-100"></audio>
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

        function toggleMedia(buttonElement) {
            const card = buttonElement.closest('.media-card');

            const mediaTarget = card.querySelector('.media-target');
            const defaultImg = card.querySelector('.default-media-img');
            const removeCheckbox = card.querySelector('.remove-checkbox');

            if (mediaTarget.style.display === "none") {
                // GERİ YÜKLE DURUMU
                mediaTarget.style.display = "flex"; // Burada flex yapıyoruz ki ortalama bozulmasın
                if (defaultImg) defaultImg.style.display = "none";

                buttonElement.classList.remove("bg-success");
                buttonElement.classList.add("bg-error");
                buttonElement.innerHTML = "Sil";

                removeCheckbox.removeAttribute("checked");
                removeCheckbox.checked = false;
            } else {
                // SİL DURUMU
                mediaTarget.style.display = "none";
                if (defaultImg) defaultImg.style.display = "block"; // Varsayılan resmi sadece blok olarak gösteriyoruz

                buttonElement.classList.remove("bg-error");
                buttonElement.classList.add("bg-success");
                buttonElement.innerHTML = "Geri Yükle";

                removeCheckbox.setAttribute("checked", "checked");
                removeCheckbox.checked = true;
            }
        }

        function openFileModal(fileUrl, extension) {
            const modal = document.getElementById('customFileModal');
            const iframe = document.getElementById('fileIframe');
            const sesContainer = document.getElementById('sesContainer');
            const sesPlayer = document.getElementById('modalSesPlayer');

            // Uzantıyı küçük harfe çevirelim (mp3, pdf, docx vs.)
            const ext = extension ? extension.toLowerCase() : '';

            // Ses dosyası ise
            if (['mp3', 'wav', 'ogg'].includes(ext)) {
                iframe.style.display = 'none';
                iframe.src = '';

                sesContainer.style.display = 'flex';
                sesPlayer.src = fileUrl;
                sesPlayer.play(); // Pop-up açılınca otomatik çalmaya başlasın
            }
            // Döküman (PDF, Word, Excel) ise
            else {
                sesContainer.style.display = 'none';
                sesPlayer.pause();
                sesPlayer.src = '';

                iframe.style.display = 'block';
                if (['doc', 'docx', 'xls', 'xlsx'].includes(ext)) {
                    const fullUrl = fileUrl.startsWith('http') ? fileUrl : window.location.origin + '/' + fileUrl.replace(/^\//, '');
                    iframe.src = `https://docs.google.com/gview?url=${encodeURIComponent(fullUrl)}&embedded=true`;
                } else {
                    iframe.src = fileUrl;
                }
            }

            modal.style.display = 'flex';
        }

        function closeFileModal() {
            const modal = document.getElementById('customFileModal');
            const iframe = document.getElementById('fileIframe');
            const sesContainer = document.getElementById('sesContainer');
            const sesPlayer = document.getElementById('modalSesPlayer');

            // İframe'i temizle
            iframe.src = '';
            iframe.style.display = 'none';

            // Sesi durdur ve kaynağı temizle
            sesPlayer.pause();
            sesPlayer.src = '';
            sesContainer.style.display = 'none';

            modal.style.display = 'none';
        }
    </script>
@endsection
