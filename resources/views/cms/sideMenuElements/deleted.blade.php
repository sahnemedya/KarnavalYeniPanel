@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header"><span class="text-warning">{{$category->name}}</span> Sayfa Listesi</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Sayfa Adı</th>
                    <th>Resim</th>
                    <th>Bağlı Sayfa</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($pages as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->title}}</th>
                        <th>
                            @if($item->image)
                                {{$item->image}}
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>
                            @if($item->parentPage)
                                {{$item->parentPage->title}}
                            @else
                                Üst Sayfası Yok
                            @endif
                        </th>


                        <th class="islemler">
                            <a onclick="restore('{{route("cms.pages.restore",$item->id)}}')"
                               class="btn bg-warning">
                                <i class="las la-recycle"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.pages.forceDelete",$item->id)}}')"
                               class="btn bg-error" title="Sil">
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
            // KALICI SİLME UYARISI
            const onay = confirm("DİKKAT! Bu alt sayfayı silerseniz bir daha asla erişemezsiniz.\n\nAyrıca bu sayfaya ait olan tüm ÇEVİRİ SAYFALARI (İngilizce vs.) ve özel dosyaları da tamamen silinecektir.\n\nKalıcı olarak silmek istediğinize emin misiniz?");

            if (onay) {
                axios.delete(route, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                }).then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        setInterval(() => window.location.reload(), 1500);
                    } else if (response.data.status === "warning") {
                        notyf.open({ type: "warning", message: response.data.message });
                    } else {
                        notyf.error(response.data.message);
                    }
                }).catch(error => {
                    notyf.error("Silme işlemi sırasında bir hata oluştu.");
                });
            }
        }

        function restore(route) {
            // GERİ YÜKLEME UYARISI
            const onay = confirm("Bu alt sayfayı geri yüklemek istediğinize emin misiniz?\n\nNot: Bu sayfaya bağlı tüm çeviri sayfaları da otomatik olarak geri yüklenecektir.");

            if (onay) {
                axios.post(route, {}, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                }).then(response => {
                    if (response.data.status === "success") {
                        setTimeout(() => {
                            notyf.success(response.data.message);
                            window.location.reload();
                        }, 1250);
                    } else if (response.data.status === "warning") {
                        notyf.open({ type: "warning", message: response.data.message });
                    } else {
                        notyf.error(response.data.message);
                    }
                }).catch(error => {
                    notyf.error("Geri yükleme sırasında bir hata oluştu.");
                });
            }
        }
    </script>
@endsection
