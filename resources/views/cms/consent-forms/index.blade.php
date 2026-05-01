@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Referanslar</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Başlık</th>
                    <th>TR Dosya</th>
                    <th>EN Dosya</th>
                    <th>Sayfa</th>
                    <th>Yayınla</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($consentForms as $item)
                    <tr>
                        <th>{{$item->title}}</th>
                        <th>
                            @if($item->trfile())
                                {{$item->trfile()}}
                            @else
                                Dosya Yok
                            @endif
                        </th>
                        <th>
                            @if($item->enfile())
                                {{$item->enfile()}}
                            @else
                                Dosya Yok
                            @endif
                        </th>
                        <th>
                            {{$item->page->title}}
                        </th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="published" value="1"
                                       onclick="activate('{{route("cms.consent-forms.publish",$item->id)}}')"
                                       @if($item->published) checked @endif >
                                <span class="switch-slider"></span>
                            </label>
                        </th>

                        <th class="islemler">
                            <a onclick="deleteFunc('{{route("cms.consent-forms.destroy",$item->id)}}')" class="btn bg-error">
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
