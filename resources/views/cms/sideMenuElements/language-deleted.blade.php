@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">
            <span class="text-warning">{{$category->name}}</span>
            Silinen Sayfalar Listesi
            @if(request('lang_id') && request('lang_id') != 1)
                (Dil: {{ strtoupper($pages->first()->language->code ?? 'Yabancı Dil') }})
            @endif
        </div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Sayfa Adı</th>

                    {{-- SADECE YABANCI DİLDEYSEK ÇEVİRİ KAYNAĞINI GÖSTER --}}
                    @if(request('lang_id') && request('lang_id') != 1)
                        <th>Çeviri Kaynağı (TR)</th>
                    @endif

                    <th>Resim</th>
                    <th>Bağlı Sayfa</th>
                    <th>Silinme Tarihi</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($pages as $item)
                    <tr data-id="{{ $item->id }}">
                        {{-- Sayfa Adı --}}
                        <th>{{$item->title}}</th>

                        {{-- Çeviri Kaynağı (TR Adı) --}}
                        @if(request('lang_id') && request('lang_id') != 1)
                            <th>
                                @if($item->originalPage)
                                    <span class="badge bg-info text-white">
                                        {{ $item->originalPage->title }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </th>
                        @endif

                        {{-- Resim --}}
                        <th>
                            @if($item->image)
                                <img src="{{$item->image()}}" width="35" height="35" alt="">
                            @else
                                Resim Yok
                            @endif
                        </th>

                        {{-- Bağlı Sayfa (Parent) --}}
                        <th>
                            @if($item->parent)
                                {{$item->parent->title}}
                            @else
                                Üst Sayfası Yok
                            @endif
                        </th>

                        {{-- Silinme Tarihi --}}
                        <th>
                            {{ $item->deleted_at->format('d.m.Y H:i') }}
                        </th>

                        {{-- İşlemler --}}
                        <th class="islemler">
                            <a onclick="restore('{{route("cms.pages.restore",$item->id)}}')"
                               class="btn bg-warning" title="Geri Yükle">
                                <i class="las la-recycle"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.pages.forceDelete",$item->id)}}')"
                               class="btn bg-error" title="Tamamen Sil">
                                <i class="las la-trash"></i>
                            </a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section("extraJs")
    <script>
        function deleteFunc(route) {
            if(!confirm('Bu kaydı tamamen silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) return;

            axios.delete(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        setInterval(function () {
                            window.location.reload();
                        }, 1500)
                    } else if (response.data.status === "warning") {
                        notyf.open({
                            type: "warning",
                            message: response.data.message
                        });
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error("Bir hata oluştu.");
                });
        }

        function restore(route) {
            axios.post(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1250)
                    } else if (response.data.status === "warning") {
                        notyf.open({
                            type: "warning",
                            message: response.data.message
                        });
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error("Bir hata oluştu.");
                });
        }
    </script>
@endsection
