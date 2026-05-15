@if($siteSetting->footer_ek)
    {!! $siteSetting->footer_ek !!}
@endif
@if(isset($pages->extraJs))
    {!! $sayfa->extraJs !!}
@endif
@yield('extraJs')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="{{asset('js/user/jquery.mmenu.all.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{asset('js/user/owl.carousel.min.js')}}"></script>
@vite(['resources/js/style.js'])

<script src="{{asset('js/user/script.js')}}"></script>


<footer class="content-space">
    <div class="max-width">
        <div class="item item1">
            <figure class="footer-logo">
                <img src="{{asset("images/site/".$siteSetting->footer_logo )}}" alt="">
            </figure>
{{--            <a class="karnaval-bulteni-btn" href="@lang('ortakMetinler.footer.abonelik_link')">@lang('ortakMetinler.footer.abonelik')</a>--}}
        </div>
        <div class="item item2">
            <h2 class="slogan">@lang('ortakMetinler.footer.slogan')</h2>
{{--            <div class="karnaval-tarihi">{{$formatliTarih}}</div>--}}
            {{--            <div class="karnaval-tarihi">2026’DA GÖRÜŞMEK ÜZERE!</div>--}}
            <div class="yer">@lang('ortakMetinler.footer.yer')</div>
        </div>
        <div class="item item3">
            <h2>@lang('ortakMetinler.footer.sponsor_ol')</h2>
            <a href="@lang('ortakMetinler.footer.sponsor_link')" class="karnaval-destekle-btn" title="Sponsorluk">@lang('ortakMetinler.footer.sponsor_destekle')</a>
        </div>
        <div class="item item4">
            <h2>@lang('ortakMetinler.footer.sosyal_medya')</h2>
            <p>@lang('ortakMetinler.footer.sosyal_mesaj')</p>
            <div class="sosyal-links">
                @if($contacts->socialMedia->facebook)
                    <a href="{{$contacts->socialMedia->facebook}}" target="_blank">
                        <img src="{{asset('images/user/index/footer/facebook.svg')}}" alt="">
                    </a>
                @endif
                @if($contacts->socialMedia->instagram)
                    <a href="{{$contacts->socialMedia->instagram}}" target="_blank">
                        <img src="{{asset('images/user/index/footer/instagram.svg')}}" alt="">
                    </a>
                @endif
                @if($contacts->socialMedia->linkedin)
                    <a href="{{$contacts->socialMedia->linkedin}}" target="_blank">
                        <img src="{{asset('images/user/index/footer/linkedin.svg')}}" alt="">
                        </a>
                @endif
                @if($contacts->socialMedia->twitter)
                    <a href="{{$contacts->socialMedia->twitter}}" target="_blank">
                        <img src="{{asset('images/user/index/footer/twitter-x.svg')}}" alt="">
                    </a>
                @endif
                @if($contacts->socialMedia->youtube)
                    <a href="{{$contacts->socialMedia->youtube}}" target="_blank">
                        <img src="{{asset('images/user/index/footer/youtube.svg')}}" alt="">
                    </a>
                @endif

            </div>
        </div>
    </div>
</footer>


<footer class="copyright content-space">
    <div class="max-width">
        <div class="copyright-left">
            <p>Copyright © {{date('Y')}} <b>{{env("APP_NAME")}}.</b></p>
        </div>

        <div class="copyright-right">
            <a href="https://www.sahnemedya.com">Sahne<b>Medya</b></a>
        </div>
    </div>
</footer>


<script>
    // Ortak Gönderim Fonksiyonu (Kod tekrarını önlemek için)
    function veriyiGonder(tur) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var url = '{{ route('aramaKayit') }}';
        var formData = new FormData();

        formData.append('arama', tur); // 'wp' veya 'tel'
        formData.append('_token', csrfToken);

        // 1. Yöntem: sendBeacon (En iyisi)
        if (navigator.sendBeacon) {
            var status = navigator.sendBeacon(url, formData);
            // Eğer beacon sıraya alınamazsa false döner, o zaman fetch deneriz.
            if (!status) {
                fetchRequest(url, csrfToken, tur);
            }
        } else {
            // 2. Yöntem: Fetch API (Yedek)
            fetchRequest(url, csrfToken, tur);
        }
    }

    // Fetch için yardımcı fonksiyon
    function fetchRequest(url, token, tur) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({arama: tur}),
            keepalive: true // ÖNEMLİ: Sayfa değişse bile isteği canlı tut
        }).catch(err => console.error("Veri gönderilemedi:", err));
    }

    function whatsAppAramasi(e, link) {
        e.preventDefault(); // Linkin hemen açılmasını engelle

        // 1. Veriyi Gönder
        veriyiGonder('wp');

        // 2. Hafif bir gecikme ile linki aç (Verinin gitmesi için zaman tanı)
        setTimeout(function () {
            window.open(link, '_blank');
        }, 300); // 300ms gecikme (Kullanıcı fark etmez ama veri kurtarır)
    }

    function telefonpAramasi(e, link) {
        e.preventDefault();

        // 1. Veriyi Gönder
        veriyiGonder('tel');

        // 2. Gecikme ile yönlendir
        setTimeout(function () {
            // Telefon aramalarında '_blank' mobilde bazen boş sekme bırakır.
            // Bu yüzden 'window.location.href' daha sağlıklıdır.
            window.location.href = link;
        }, 300);
    }
</script>



@yield('js')
</body>
</html>
