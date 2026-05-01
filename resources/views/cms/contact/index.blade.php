@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">İletişim Bilgileri</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Adı</th>
                    <th>İlçe - İl - Ülke</th>
                    <th>Yetkili</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($contacts as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->name}}</th>
                        <th>{{$item->state}} - {{$item->city}} - {{$item->country}}</th>
                        <th>
                            {{$item->person}}
                        </th>
                        <th class="islemler">
                            @if($item->socialMedia)
                                <a href="{{route("cms.socialMedia.edit",$item->socialMedia->id)}}"
                                   class="btn bg-light" title="Sosyal Medya Bilgileri Güncelle">
                                    <i class="las la-link"></i>
                                </a>
                            @else
                                <a href="{{route("cms.socialMedia.create",$item->id)}}"
                                   class="btn bg-light" title="Sosyal Medya Bilgileri Ekle">
                                    <i class="las la-link"></i>
                                </a>
                            @endif
                            <a href="{{route("cms.contacts.edit",$item->id)}}"
                               class="btn bg-primary" title="Düzenle">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.contacts.destroy",$item->id)}}')"
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
