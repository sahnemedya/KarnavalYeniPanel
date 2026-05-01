@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Blade Listesi</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md" style="width:100%">

                <thead>
                <tr>
                    <th>Seo Title</th>
                    <th>Geo Title</th>
                    <th>Sayfa</th>
                    <th>Canonical</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($seos as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->title}}</th>
                        <th>{{$item->geo_title}}</th>
                        <th>{{$item->page_id}}</th>
                        <th>
                            {{$item->canonical}}
                        </th>
                        <th class="islemler">
                            <a href="{{route("cms.seos.edit",$item->id)}}"
                               class="btn bg-primary" title="Düzenle">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.seos.destroy",$item->id)}}')"
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
                        setInterval(function (){
                            window.location.reload();
                        },1500)
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


    </script>
@endsection
