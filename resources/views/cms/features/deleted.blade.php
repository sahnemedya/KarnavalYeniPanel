@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Silinen Referanslar</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Sıralama</th>
                    <th>Sayfa Adı</th>
                     <th>Kaçıncı Gün</th>
                    <th>Başlık</th>
                    <th>Resim</th>
                    <th>Yayınla</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($features as $item)
                    <tr>

                        <th>{{$item->hit}}</th>
                        <th>{{$item->pages->title}}</th>
                        <th>{{$item->gun}}</th>
                        <th>{{$item->name}}</th>
                        <th>
                            @if($item->image())
                                <figure data-fancybox="ozellikler" data-src="{{$item->image()}}"
                                        data-caption="{{$item->name}}">
                                    <img src="{{$item->image()}}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="published" value="1"
                                       onclick="activate('{{route("cms.features.publish",$item->id)}}')"
                                       @if($item->published) checked @endif >
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th class="islemler">
                            <a onclick="restore('{{route("cms.features.restore",$item->id)}}')"
                               class="btn bg-warning" title="Geri Yükle">
                                <i class="las la-recycle"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.features.forceDelete",$item->id)}}')"
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
                    notyf.error(response.data.message);
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
                        setTimeout(function () {
                            notyf.success(response.data.message);
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
