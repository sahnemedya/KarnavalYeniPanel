@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Yetki Listesi</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md" style="width:100%">

                <thead>
                <tr>
                    <th>Kullanıcı</th>
                    <th>Yetki Rolü</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($roleUser as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->user->name}}</th>
                        <th>
                            {{$item->role->name}}
                        </th>
                        <th class="islemler">
                            <a href="{{route("cms.role-user.edit",$item->id)}}"
                               class="btn bg-primary" title="Güncelle">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.role-user.destroy",$item->id)}}')"
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
