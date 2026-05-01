@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">
            Karnaval Sezonu
            {{-- Eğer TR dışında bir dil seçiliyse başlıkta belirt --}}
            @if(request('lang_id') && request('lang_id') != 1)
                @php
                    $currentLang = $languages->where('id', request('lang_id'))->first();
                @endphp
                (Dil: {{ strtoupper($currentLang->code ?? '') }})
            @endif
        </div>
        <div class="card-body">
            <table id="datatable" data-page-length="-1" class="display stripe table-responsive-sm table-responsive-md" style="width:100%">


                <thead>
                <tr>
                    <th>id</th>
                    <th>Sıralama</th>
                    <th>Karnaval Yılı</th>
                    <th>Sezon Başlangıçı</th>
                    <th>Karnaval Tarihi Başlangıçı</th>
                    <th>Karnaval Tarihi Bitiş</th>
                    <th>Güncel Sezon</th>
                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>

                @foreach($karnavalSezonlari as $item)
                    <tr>
                        <th>{{$item->id}}</th>
                        <th>{{$item->hit}}</th>
                        <th>{{$item->karnaval_yili}}</th>
                        <th>{{$item->sezon_baslangici}}</th>
                        <th>{{$item->karnaval_tarihi_baslangic}}</th>
                        <th>{{$item->karnaval_tarihi_bitis}}</th>
                        <th>
{{--                            <label class="switch">--}}
{{--                                <input type="checkbox" name="active" value="1"--}}
{{--                                       onclick="activate('{{ route("cms.karnaval-sezonu.activate",$item->id) }}','published')"--}}
{{--                                       @if($item->published) checked @endif>--}}
{{--                                <span class="switch-slider"></span>--}}
{{--                            </label>--}}
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       class="karnaval-status-switch"
                                       onchange="activate(this, '{{ route('cms.karnaval-sezonu.activate', $item->id) }}')"
                                       @if($item->published) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th class="islemler">
                            <a href="{{ route("cms.karnaval-sezonu.edit",$item->id) }}"
                               class="btn bg-primary" title="Düzenle">
                                <i class="las la-pen"></i>
                            </a>
                            <a onclick="deleteFunc('{{route("cms.karnaval-sezonu.destroy",$item->id)}}')" class="btn bg-error" title="Sil">
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
        // function activate(route) {
        //     axios.post(route, {
        //         headers: {
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //         }
        //     })
        //         .then(response => {
        //             if (response.data.status === "success") {
        //                 notyf.success(response.data.message);
        //             }
        //             else {
        //                 notyf.error(response.data.message);
        //             }
        //         })
        //         .catch(error => {
        //             notyf.error(response.data.message);
        //         });
        // }
        function activate(element, route) {
            // Tıklandıktan hemen sonra checkbox'ın aldığı kesin değer (true veya false)
            const targetState = element.checked;

            // Çift tıklamayı ve UI'ın bug'a girmesini engellemek için işlem bitene kadar tıklamayı kapat
            element.disabled = true;

            axios.post(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    element.disabled = false; // İşlem bitti, kilidi aç

                    if (response.data.status === "success") {
                        notyf.success(response.data.message);

                        // Eğer tıkladığımız buton "Aktif" (true) durumuna geçtiyse:
                        if (targetState === true) {
                            // Diğer tüm switchleri bul ve kapalı (false) hale getir
                            document.querySelectorAll('.karnaval-status-switch').forEach(checkbox => {
                                if (checkbox !== element) {
                                    checkbox.checked = false;
                                }
                            });
                        }
                        // Eğer buton pasife (false) çekildiyse, zaten kapalı olacağı için ekstra bir şeye gerek yok.

                    } else {
                        notyf.error(response.data.message);
                        // Başarısız olduysa butonu görsel olarak eski haline al
                        element.checked = !targetState;
                    }
                })
                .catch(error => {
                    element.disabled = false; // Kilidi aç
                    notyf.error("Bir hata oluştu, lütfen sayfayı yenileyin.");
                    // Hata durumunda butonu görsel olarak eski haline al
                    element.checked = !targetState;
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
