@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Kurumsal Kimlik</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Kurumsal Kimlik Adı</th>
                    <th>Dosya Adı</th>
                    <th>Resim</th>
                    <th>Yayınla</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($corporateIdentity as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->file}}</td>
                        {{-- Resim gösterimi --}}
                        <td>
                            @if($item->image())
                                <img src="{{ $item->image() }}" alt="{{ $item->name }}" style="max-height: 50px;">
                            @else
                                Resim Yok
                            @endif
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="published" value="1"
                                       onclick="activate('{{route("cms.corporateIdentity.publish",$item->id)}}')"
                                       @if($item->published) checked @endif >
                                <span class="switch-slider"></span>
                            </label>
                        </td>
                        <td class="islemler">
                            <a onclick="deleteFunc('{{route("cms.corporateIdentity.destroy",$item->id)}}')" class="btn bg-error">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
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
                    notyf.error(error.response.data.message);
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
                    notyf.error(error.response.data.message);
                });
        }
    </script>
@endsection
