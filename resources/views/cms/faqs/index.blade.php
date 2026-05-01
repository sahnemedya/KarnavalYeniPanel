@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">
            Sıkça Sorulan Sorular
            {{-- Eğer TR dışında bir dil seçiliyse başlıkta belirt --}}
            @if(request('lang_id') && request('lang_id') != 1)
                @php
                    $currentLang = $languages->where('id', request('lang_id'))->first();
                @endphp
                (Dil: {{ strtoupper($currentLang->code ?? '') }})
            @endif
        </div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Soru</th>
                    <th>Cevap</th>
                    <th>Sayfa</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($faqs as $item)
                    <tr>
                        <th>{{$item->question}}</th>
                        <th>{{ Str::limit(strip_tags($item->answer), 50) }}</th>
                        <th>
                            {{$item->page->title ?? 'Sayfa Yok'}}
                        </th>
                        <th class="islemler">
                            <a onclick="deleteFunc('{{route("cms.faqs.destroy",$item->id)}}')" class="btn bg-error">
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
{{-- extraJs bölümü mevcut haliyle aynı kalabilir --}}
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
