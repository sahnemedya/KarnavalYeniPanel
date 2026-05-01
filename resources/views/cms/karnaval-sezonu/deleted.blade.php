@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Silinen Karnaval Sezonu</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Karnaval Yılı</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($karnavalSezonlari as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->karnaval_yili}}</th>

                        <th class="islemler">
                            <a onclick="restore('{{route("cms.karnaval-sezonu.restore",$item->id)}}')"
                               class="btn bg-warning" title="Geri Yükle">
                                <i class="las la-recycle"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.karnaval-sezonu.forceDelete",$item->id)}}')"
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
