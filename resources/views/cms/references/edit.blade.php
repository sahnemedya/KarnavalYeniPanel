@extends("cms.partial.layout")

@section("extraCss")
    <link rel="stylesheet" href="{{asset("plugins/ckeditor/skins/moono/editor.css")}}">
    <script src="{{asset("plugins/ckeditor/lang/tr.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/styles.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/ckeditor.js")}}"></script>
@endsection
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Referans Düzenle</div>
            <div class="card-body">
                <form action="{{ route('cms.references.update', $references->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="custom-media-grid grid-reference mb-4">

                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>Ana Resim</h3>
                                <div class="media-preview">
                                    <a href="{{ $references->image() }}" data-fancybox="gallery" class="media-target"
                                       style="display: {{ $references->image ? 'flex' : 'none' }};">
                                        <img src="{{ $references->image() }}" alt="Resim 1">
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}"
                                         class="default-media-img" alt="Silindi"
                                         style="display: {{ $references->image ? 'none' : 'block' }};">
                                </div>
                                <input type="file" name="image" id="image" class="form-control"
                                       placeholder="Resim Seçin">
                                <button type="button" class="btn delete-image-btn bg-error" onclick="toggleMedia(this)"
                                        style="display: {{ $references->image ? 'inline-block' : 'none' }};">Sil
                                </button>
                                <input type="checkbox" name="remove_image" class="d-none remove-checkbox">
                            </figure>
                        </div>


                    </div>


                    <label for="sezonId" class="form-label" style="display: block; margin-bottom: 8px;">Karnaval
                        Sezonu:</label>
                    <select name="sezon_id" id="sezonId" title="Hangi Karnaval Sezonuna ait ise o seçilmeli.">
                        <option value="">Tüm Sezonlar</option>
                        @foreach($karnavalSezonlari as $item)
                            <option value="{{$item->id}}" @if($references->sezon_id == $item->id) selected @endif>{{$item->karnaval_yili}}</option>
                        @endforeach
                    </select>
                    <label for="name">Referans Adı</label>
                    <input type="text" name="name" id="name" placeholder="Referans Adı" value="{{$references->name}}" required>


                    <label for="type_id">Referans Türü</label>
                    <select name="type_id" id="type_id">
                        <option value="">Tümü</option>
                        @foreach($refaceTypes as $item)
                            <option value="{{$item->id}}" @if($references->type_id == $item->id) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası" value="{{$references->hit}}">

                    <label for="url">Yönlendirilecek URL</label>
                    <input type="text" name="url" id="url" placeholder="Yönlendirilecek URL" value="{{$references->url}}">
                    @if($languages->count()>1)
                        <label for="langId">Dil</label>
                        <select name="lang_id" id="langId">
                            @foreach($languages as $item)
                                <option value="{{$item->id}}" @if($references->lang_id == $item->id) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
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

    <script>
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
    </script>

@endsection
