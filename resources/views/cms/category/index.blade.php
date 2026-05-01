@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Kategoriler</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Kategori Adı</th>
                    <th>Resim</th>
                    <th title="Navbarda (Üst Menüde) Göster">NG</th>
                    <th title="Ana Sayfada Göster">AG</th>
                    <th title="Footerda Göster">FG</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($categories as $item)
                    <tr>
                        <th>{{$item->name}}</th>
                        <th>
                            @if($item->image())
                                <figure data-fancybox="Kategoriler" data-src="{{$item->image()}}"
                                        data-caption="{{$item->name}}">
                                    <img src="{{$item->image()}}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1" onclick="activate('{{route("cms.category.activate",$item->id)}}','show_menu')"
                                       @if($item->show_menu) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1" onclick="activate('{{route("cms.category.activate",$item->id)}}','show_homepage')"
                                       @if($item->show_homepage) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1" onclick="activate('{{route("cms.category.activate",$item->id)}}','show_footer')"
                                       @if($item->show_footer) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th class="islemler">
                            <a href="{{route("cms.category.edit",$item->id)}}" class="btn bg-primary">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.category.destroy",$item->id)}}')" class="btn bg-error">
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
        function activate(route, type) {
            axios.post(route, {
                type: type
            }, {
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
                .catch(error => {
                    notyf.error("Bir hata oluştu.");
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
