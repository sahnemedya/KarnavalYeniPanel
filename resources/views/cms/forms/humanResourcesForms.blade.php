@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header">Formlar</div>
        <div class="card-body">
                <h1>Gelen İnsan Kaynakları Mailleri Formları</h1>
                <div class="inbox">
                    <div class="mail-list">
                        <ul>
                            @if($gelenMailler->count()==0)
                                <li><a href="javascript:void(0)">Henüz Gelen Mail Bulunmamaktadır</a></li>
                            @endif
                            @foreach($gelenMailler as $mail)
                                <li class="@if($mail->markRead == 0) unread @endif" id="mail{{$mail->id}}">
                                    <a href="javascript:void(0)" onclick="getForm({{$mail->id}})">
                                        <div class="img">{{\Illuminate\Support\Str::limit($mail->adSoyad,1,'')}}</div>
                                        <div class="gonderen-bilgi">
                                            <div class="gonderen">{{\Illuminate\Support\Str::limit($mail->adSoyad,20,'...')}}</div>
                                            <div class="aciklama">{{\Illuminate\Support\Str::limit($mail->email,20,'...')}}</div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mail-detail" id="mailDetail">
                    </div>
                </div>
        </div>
    </div>

@endsection
@section("extraJs")
    <script>
        function getForm(mailId) {
            axios.get(`/cms/forms/get-Imail?id=${mailId}`)
                .then(response => {
                    document.getElementById("mailDetail").innerHTML = response.data.durum ?? response.data;
                })
                .catch(error => {
                    console.error("Mail içeriği alınamadı:", error);
                });
        }

        // Diğer mevcut fonksiyonlar burada kalabilir (activate, deleteFunc vs.)
    </script>
@endsection
