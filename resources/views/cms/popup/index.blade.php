@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Pop-Up'lar</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Resim</th>
                    <th>Mobil Resim</th>
                    <th>Yayınla</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($popups as $item)
                    <tr>
                        <th>{{$item->title}}</th>
                        <th>{{$item->image}}</th>
                        <th>{{$item->mobile_image}}</th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="published" value="1"
                                       onclick="activate('{{route("cms.popup.publish",$item->id)}}')"
                                       @if($item->published) checked @endif >
                                <span class="switch-slider"></span>
                            </label>
                        </th>

                        <th class="islemler">
                            <a onclick="deleteFunc('{{route("cms.popup.destroy",$item->id)}}')" class="btn bg-error">
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
