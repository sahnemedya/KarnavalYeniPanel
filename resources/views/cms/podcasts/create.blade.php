@extends("cms.partial.layout")

@section("content")
    <div class="row">

        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">
                Podcast Ekle
            </div>

            <div class="card-body">

                <form action="{{ route('cms.podcast.store') }}" method="post">
                    @csrf

                    <div id="podcasts_wrapper">

                        {{-- Default Podcast --}}
                        <div class="podcast-row"
                             style="margin-bottom:40px; border-bottom:1px dashed #ccc; padding-bottom:20px;">

                            <h3 class="podcast-title">1. Podcast</h3>

                            <label>Podcast Başlığı</label>
                            <input type="text"
                                   name="name[]"
                                   placeholder="Podcast başlığı"
                                   required>

                            <label>Podcast Linki</label>
                            <input type="url"
                                   name="link[]"
                                   placeholder="https://..."
                                   required>

                            <label>Yayın Tarihi</label>
                            <input type="date"
                                   name="published[]">
                        </div>

                    </div>

                    <div style="text-align:right; margin-bottom:20px;">
                        <input type="button"
                               value="+ Yeni Podcast Ekle"
                               onclick="addPodcast()">
                    </div>

                    {{-- Sayfa --}}
                    @if($selectedPage)
                        <label>Bağlı Olduğu Sayfa</label>
                        <select name="page_id" required>
                            <option value="{{ $selectedPage->id }}">
                                {{ $selectedPage->title }}
                            </option>
                        </select>
                    @else
                        <p style="color:red;">❌ Sayfa bulunamadı</p>
                    @endif

                    {{-- Dil --}}
                    @if($languages->count() > 1)
                        <label>Dil</label>
                        <select name="lang_id">
                            @foreach($languages as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <input type="submit" value="Kaydet">
                </form>

            </div>
        </div>

    </div>
@endsection


@section("extraJs")
    <script>

        let counter = 1;

        function addPodcast()
        {
            let wrapper = document.getElementById("podcasts_wrapper");

            let html = `
        <div class="podcast-row"
             style="margin-bottom:40px; border-bottom:1px dashed #ccc; padding-bottom:20px;">

            <h3 class="podcast-title">... Podcast</h3>

            <label>Podcast Başlığı</label>
            <input type="text" name="name[]" required>

            <label>Podcast Linki</label>
            <input type="url" name="link[]" required>

            <label>Yayın Tarihi</label>
            <input type="date" name="published[]">

            <div style="text-align:right; margin-top:10px;">
                <input type="button"
                       value="Sil"
                       onclick="removePodcast(this)"
                       style="background:#dc3545;color:#fff;border:none;padding:8px 14px;cursor:pointer;">
            </div>

        </div>
    `;

            wrapper.insertAdjacentHTML("beforeend", html);

            reorderPodcasts();
        }

        function removePodcast(btn)
        {
            btn.closest(".podcast-row").remove();
            reorderPodcasts();
        }

        function reorderPodcasts()
        {
            let rows = document.querySelectorAll(".podcast-row");

            rows.forEach((row, index) => {
                row.querySelector(".podcast-title").innerText =
                    (index + 1) + ". Podcast";
            });
        }

    </script>
@endsection
