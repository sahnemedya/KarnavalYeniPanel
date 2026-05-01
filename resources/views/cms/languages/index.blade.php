@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Dil Listesi (Blade)</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md" style="width:100%">

                <thead>
                <tr>
                    <th>Dil</th>
                    <th>Dil Kodu</th>
                    <th>Durumu</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($languages as $lang)
                    <tr data-id="{{ $lang->id }}">
                        <th>{{$lang->name}}</th>
                        <th>{{$lang->code}}</th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       onclick="activate('{{route("cms.languages.activate",$lang->id)}}')"
                                       @if($lang->active) checked @endif >
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th>
                            <a onclick="deleteFunc('{{route("cms.languages.destroy",$lang->id)}}',{{$lang->id}})"
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
        function deleteFunc(route, id) {
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
