@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-12">
            <div class="card-header"><span class="text-warning">{{$role->name}}</span> Rol Yetkilerini Ayarla</div>
            <div class="card-body">

                @foreach($permissions as $item)
                    <div class="card-header row">
                        <div class="col-sm-3">
                            {{$item->name}}
                        </div>
                        <div class="col-sm-1">
                            @if($role->permissions->contains($item->id))
                                <a class="btn bg-error"
                                   onclick="togglePermission('{{ route('cms.rolePermissions.toggle', ['role' => $role->id, 'permission' => $item->id]) }}',this)">
                                    Kaldır
                                </a>
                            @else
                                <a class="btn bg-success"
                                   onclick="togglePermission('{{ route('cms.rolePermissions.toggle', ['role' => $role->id, 'permission' => $item->id]) }}',this)">
                                    Ekle
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
@section("extraJs")

    <script>
        function togglePermission(route, el) {
            axios.post(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        setTimeout(function (){
                            window.location.reload();
                        },600)

                    } else {
                        notyf.success(response.data.message);
                        setTimeout(function (){
                            window.location.reload();
                        },600)
                    }
                })
                .catch(error => {
                    notyf.error(response.data.message);
                });
        }
    </script>

@endsection
