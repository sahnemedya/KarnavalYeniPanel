@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Doktor Listesi</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md" style="width:100%">

                <thead>
                <tr>
                    <th>Doktor</th>
                    <th>Resim 1</th>
                    <th>Resim 2</th>
                    <th>Birimi</th>
                    <th>Eklenme Tarihi</th>
                    <th title="Doktorlar Sayfasında Göster">Göster</th>
                    <th title="Anasayfada Göster">Anasayfa Göster</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($doctors as $item)
                    <tr data-id="{{ $item->id }}">
                        <th>{{$item->title}}</th>
                        <th>
                            @if($item->image())
                                <figure data-fancybox="Sayfalar" data-src="{{ $item->image() }}"
                                        data-caption="{{ $item->title }}">
                                    <img src="{{ $item->image() }}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>

                        {{-- İkinci Resim --}}
                        <th>
                            @if($item->image2())
                                <figure data-fancybox="Sayfalar" data-src="{{ $item->image2() }}"
                                        data-caption="{{ $item->title }} - 2. Resim">
                                    <img src="{{ $item->image2() }}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>
                        <th>{{$item->medicalUnit->title}}</th>
                        <th>
                            {{$item->updated_at->diffForHumans()}}
                        </th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       onclick="activate('{{ route("cms.doctors.activate",$item->id) }}','show')"
                                       @if($item->show) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>

                        {{-- Ana sayfada gösterim --}}
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       onclick="activate('{{ route("cms.doctors.activate",$item->id) }}','show_homepage')"
                                       @if($item->show_homepage) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th class="islemler">
                            <a href="{{route("cms.doctors.edit",$item->id)}}"
                               class="btn bg-primary" title="Düzenle">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.doctors.destroy",$item->id)}}')"
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

        function publishPage(route) {
            axios.post(route, {
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
    </script>
@endsection
