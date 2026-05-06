@extends('panel.partial.master')
@section("meta-title","Ön Kayıt Gelen Başvurular")
@section('extraCss')
    <script src="{{asset('js/panel/jquery.min.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection
@section('content')

    {{--    <div class="mail-box">--}}

    {{--        <div class="mail-list">--}}

    {{--            <div class="title">Gelen Mailler</div>--}}

    {{--            @foreach($gelenMailler as $mail)--}}
    {{--                <a href="javascript:void(0)" id="mail{{$mail->id}}" onclick="getForm({{$mail->id}})" class="mail @if($mail->markRead == 0) unread @endif">--}}
    {{--                    <div class="mail-konu">{{\Illuminate\Support\Str::limit($mail->konu,20,'...')}}</div>--}}
    {{--                    <div class="mail-gonderen-adi">{{\Illuminate\Support\Str::limit($mail->adSoyad,20,'...')}}</div>--}}

    {{--                </a>--}}
    {{--            @endforeach--}}
    {{--        </div>--}}
    {{--        <div class="mail-detail" id="mailDetail">--}}

    {{--        </div>--}}
    {{--    </div>--}}

    <div class="mail-box">
        <h1>Gelen Ön Kayıt Formları</h1>
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
                                    <div class="aciklama">{{\Illuminate\Support\Str::limit($mail->konu,20,'...')}}</div>
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
@endsection

@section('extraJs')
    <script>
        function getForm(formID) {
            $.ajax({
                url: '{{route('yonetim.getOnKayitMail')}}',
                type: 'POST',
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'id': formID},
                success: function (message) {
                    document.getElementById('mailDetail').innerHTML = message.durum;
                    const mailID = "#mail"+formID;
                    document.querySelector(mailID).classList.remove('unread');
                },
                error: function (hata) {
                    console.log(hata.durum);
                }
            });
        }

    </script>

@endsection
