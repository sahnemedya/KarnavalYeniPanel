@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header display-flex justify-between">
            <span> <span class="text-warning">{{$page->title}}</span> Galeri Resimleri</span>
            <a href="{{route("cms.gallery.destroyPageGallery",$page->id)}}" class="btn bg-error"
               title="{{$page->title}} sayfa gaerisine ait tüm resimleri siler."
            >Sayfa Galerisi Sil</a>
        </div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Sayfa Adı</th>
                    <th>Resim</th>
                    <th>Sıra</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($galleries as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->page->title}}</th>
                        <th>
                            @if($item->image())
                                <figure data-fancybox="{{$item->page->title}} Galeri" data-src="{{$item->image()}}"
                                        data-caption="{{$item->page->title}}">
                                    <img src="{{$item->image()}}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>
                            {{$item->hit}}
                        </th>
                        <th>
                            <a onclick="deleteFunc('{{route("cms.gallery.destroy",$item->id)}}')"
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
