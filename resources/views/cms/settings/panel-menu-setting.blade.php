@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Panel Sol Menu Ayarları</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Kategori Adı</th>
                    <th title="Footerda Göster">Panel Menüde Göster</th>
                </tr>
                </thead>

                <tbody>
                @foreach($categories as $item)
                    <tr>
                        <th>{{$item->name}}</th>

                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1" onclick="activate('{{route("cms.settings.panel-menu.update",$item->id)}}')"
                                       @if($item->show_panel) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
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
            axios.put(route, {
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
                    notyf.error("Bir hata oluştu.");
                });
        }

    </script>
@endsection
