@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Galeri Listesi</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md" style="width:100%">

                <thead>
                <tr>
                    <th>Sayfa Adı</th>
                    <th>Resim Sayısı</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($galleries as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->page->title}} Galerisi</th>
                        <th>{{$item->page->gallery->count()}} Adet</th>
                        <th class="islemler">
                            <a href="{{route("cms.gallery.create",$item->page_id)}}"
                               class="btn bg-success" title="{{$item->page->title}} Galeri Resmi Ekle">
                                <i class="las la-plus"></i>
                            </a>
                            <a href="{{route("cms.gallery.pageGallery",$item->page_id)}}"
                               class="btn bg-secondary" title="{{$item->page->title}} Galerisini Görüntüle">
                                <i class="las la-ellipsis-h"></i>
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
        function deleteFunc(route, id) {
            axios.delete(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        window.location.reload();
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
                    notyf.error(response.data.message);
                });
        }

        function activate(route) {

            axios.put(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                    } else if (response.data.status === "warning") {
                        notyf.open({
                            type: "warning",
                            message: response.data.message
                        });
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(response => {
                    notyf.error(response.data.message);
                })
        }
    </script>
@endsection
