@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Basın Kiti</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Adı</th>
                    <th>Resmi</th>
                    <th>Dosya</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($pressKits as $item)
                    <tr>
                        <th>{{$item->name}}</th>
                        <th>
                            @if($item->image())
                                <figure data-fancybox="Page List" data-src="{{$item->image()}}"
                                        data-caption="{{$item->name}}">
                                    <img src="{{$item->image()}}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>
                            <a href="{{$item->file()}}" download="{{$item->file}}">
                                {{$item->name}} <i class="las la-download"></i>
                            </a>

                        </th>

                        <th class="islemler">
                            <a onclick="deleteFunc('{{route("cms.press-kit.destroy",$item->id)}}')" class="btn bg-error">
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
        function activate(route) {
            axios.post(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                    }
                    else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error(response.data.message);
                });
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
                    }
                    else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error(response.data.message);
                });
        }
    </script>
@endsection
