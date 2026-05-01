@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Yorumcular</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Sıralama</th>
                    <th>Başlık</th>
                    <th>Resim</th>
                    <th>Dil</th>
                    <th>Yayınla</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($comments as $item)
                    <tr>

                        <th>{{$item->hit}}</th>
                        <th>{{$item->name}}</th>
                        <th>
                            @if($item->image())
                                <figure data-fancybox="Yorumcu" data-src="{{$item->image()}}"
                                        data-caption="{{$item->name}}">
                                    <img src="{{$item->image()}}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>{{ $item->languages->name }}</th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="published" value="1"
                                       onclick="activate('{{route("cms.comments.publish",$item->id)}}')"
                                       @if($item->published) checked @endif >
                                <span class="switch-slider"></span>
                            </label>
                        </th>


                        <th class="islemler">
                            <a href="{{ route("cms.comments.edit",$item->id) }}"
                               class="btn bg-primary" title="Düzenle">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{ route("cms.comments.destroy",$item->id) }}')"
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


        function showHomePage(route) {
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
    </script>
@endsection
