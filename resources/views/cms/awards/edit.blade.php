@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Ödül Düzenle</div>
            <div class="card-body">
                <form action="{{ route('cms.awards.update', $award->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="custom-media-grid grid-reference mb-4">
                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>Ödül Resmi</h3>
                                <div class="media-preview">
                                    <a href="{{ $award->image() }}" data-fancybox="gallery" class="media-target"
                                       style="display: {{ $award->image ? 'flex' : 'none' }};">
                                        <img src="{{ $award->image() }}" alt="{{ $award->name }}">
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}"
                                         class="default-media-img" alt="Silindi"
                                         style="display: {{ $award->image ? 'none' : 'block' }};">
                                </div>
                                <input type="file" name="image" id="image" class="form-control"
                                       placeholder="Resim Seçin">
                                <button type="button" class="btn delete-image-btn bg-error" onclick="toggleMedia(this)"
                                        style="display: {{ $award->image ? 'inline-block' : 'none' }};">Sil
                                </button>
                                <input type="checkbox" name="remove_image" class="d-none remove-checkbox">
                            </figure>
                        </div>
                    </div>

                    <label for="name">Ödül Adı</label>
                    <input type="text" name="name" id="name" placeholder="Ödül Adı" value="{{ $award->name }}" required>

                    <label for="prize_date">Ödül Tarihi</label>
                    <input type="date" name="prize_date" id="prize_date"
                           value="{{ $award->prize_date?->format('Y-m-d') }}">

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası" value="{{ $award->hit }}">

                    <label for="published" style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="published" id="published" value="1" {{ $award->published ? 'checked' : '' }}>
                        Yayınla
                    </label>

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
                mediaTarget.style.display = "flex";
                if (defaultImg) defaultImg.style.display = "none";

                buttonElement.classList.remove("bg-success");
                buttonElement.classList.add("bg-error");
                buttonElement.innerHTML = "Sil";

                removeCheckbox.removeAttribute("checked");
                removeCheckbox.checked = false;
            } else {
                mediaTarget.style.display = "none";
                if (defaultImg) defaultImg.style.display = "block";

                buttonElement.classList.remove("bg-error");
                buttonElement.classList.add("bg-success");
                buttonElement.innerHTML = "Geri Yükle";

                removeCheckbox.setAttribute("checked", "checked");
                removeCheckbox.checked = true;
            }
        }
    </script>
@endsection
