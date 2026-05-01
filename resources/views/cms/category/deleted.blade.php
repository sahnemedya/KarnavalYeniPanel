@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Silinen Kategoriler</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th style="text-align: center;">Kategori Adı</th>
                    <th style="text-align: center;">İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($categories as $item)
                    <tr>
                        <th>{{$item->name}}</th>

                        <th class="islemler">
                            <a onclick="restoreCategory('{{route("cms.category.restore",$item->id)}}')" class="btn bg-warning">
                                <i class="las la-recycle"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.category.forceDelete",$item->id)}}')" class="btn bg-error">
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
        function restoreCategory(route) {
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
                        },1250)
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
