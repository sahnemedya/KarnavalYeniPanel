@extends('user.partial.master')

@section('content')

    @if($sliders->count() > 0)
        <div class="swiper swiper-carousel-slider swiper-main-slider">
            <div class="swiper-wrapper">
                @foreach($sliders as $slider)
                    <div class="swiper-slide">
                        <a @if($slider->url) href="{{$slider->url}}" @endif class="item">
                            {{-- Web Görseli --}}
                            <img class="web-slider" src="{{$slider->image()}}" alt="{{$slider->title}}">

                            {{-- Mobil Görseli --}}
                            <img class="mobil-slider" src="{{$slider->mobilImage()}}" alt="{{$slider->title}}">
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="swiper-pagination"></div>
        </div>
    @endif

    <h1 style="display: none">{{env("APP_NAME")}}</h1>

    {{-- Anasayfa Verileri Başlangıç --}}

    <div class="anasayfa-duyurular content-space">
        <div class="max-width">
            <h2 class="anasayfa-h2">@lang('ortakMetinler.duyuru.baslik')</h2>

            {{-- Swiper Ana Kapsayıcı --}}
            <div class="swiper swiper-duyurular">
                <div class="swiper-wrapper">

                    @php
                        // Slide sayısı 4'ten azsa içeriği çoğalt
                        $duyurularLoop = count($duyurular) < 4
                            ? collect($duyurular)->concat($duyurular)->concat($duyurular)
                            : $duyurular;
                    @endphp

                    @foreach($duyurular as $duyuru)
                        {{-- Swiper Slide Elemanları --}}
                        <div class="swiper-slide">
                            <a href="{{$duyuru->slug}}" class="item">
                                <img src="{{$duyuru->image()}}" alt="{{$duyuru->title}}">
                                <h3>{{ \Illuminate\Support\Str::limit($duyuru->title, 50) }}</h3>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
            <a href="@lang('ortakMetinler.duyuru.link')"
               class="incele">@lang('ortakMetinler.butonlar.tumunu_incele')</a>
        </div>
    </div>


    <div class="anasayfa-karnaval-bilgileri content-space">
        <h2 class="anasayfa-h2">@lang('ortakMetinler.karnaval_bilgisi.baslik')</h2>
        <div class="swiper swiper-karnaval-bilgileri">
            <div class="swiper-wrapper">
                @foreach($karnavalBilgiler as $karnavalBilgi)
                    {{-- Kategoriye bağlı sayfa var mı diye garanti altına alıyoruz --}}

                    <div class="swiper-slide">
                        <a href="{{ $karnavalBilgi->slug }}" class="item">
                            <figure>

                                @if(empty($karnavalBilgi->image))
                                    <img src="{{asset("images/site/nophoto.png")}}"
                                         alt="{{ $karnavalBilgi->title }}"
                                         loading="lazy"
                                         decoding="async">
                                @else
                                    <img src="{{ $karnavalBilgi->image() }}"
                                         alt="{{ $karnavalBilgi->title }}"
                                         loading="lazy"
                                         decoding="async">
                                @endif
                            </figure>
                            <h3>{{ $karnavalBilgi->title }}</h3>
                        </a>
                    </div>

                @endforeach


            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="anasayfa-adanadan-bilgiler content-space">
        <div class="max-width">
            <h2 class="anasayfa-h2">@lang('ortakMetinler.adana_bilgisi.baslik')</h2>
            <div class="adana-bilgileri">
                @foreach($adanada as $adana)
                    <a href="{{ $adana->slug }}" class="bilgi">
                        <figure>
                            @if(empty($adana->icon))
                                <img src="{{asset("images/site/nophoto.png")}}"
                                     alt="{{ $adana->title }}"
                                     loading="lazy"
                                     decoding="async">
                            @else
                                <img src="{{ $adana->icon() }}"
                                     alt="{{ $adana->title }}">
                            @endif
                        </figure>
                        <h3>{{ $adana->title }}</h3>
                        <div class="incele">@lang('ortakMetinler.butonlar.detayli_incele')</div>
                    </a>
                @endforeach
            </div>
            <a href="@lang('ortakMetinler.adana_bilgisi.link')"
               class="incele">@lang('ortakMetinler.butonlar.tumunu_incele')</a>
        </div>
    </div>

    <div class="anasayfa-nisanda-adanada content-space">
        <figure>
            <img src="{{asset("images/user/index/nisanda-adanada.jpg")}}" alt="@lang('ortakMetinler.nisanda.baslik')">
        </figure>
        <div class="max-width">
            <div class="text">
                <div class="el-yazi">@lang('ortakMetinler.nisanda.baslik')</div>

                <p>
                    @lang('ortakMetinler.nisanda.aciklama')

                </p>
                <a href="@lang('ortakMetinler.nisanda.link')"  class="incele">@lang('ortakMetinler.butonlar.detayli_incele')</a>
            </div>
        </div>
    </div>


        <div class="anasayfa-izlenimler content-space">
            <div class="max-width">
                <h2 class="anasayfa-h2">@lang('ortakMetinler.izlenimler.baslik')</h2>

                @php
                    /** @var \Illuminate\Database\Eloquent\Collection $izlenimler */
                    /** @var int $izlenimlerCount */

                    /* SWIPER LOOP HİLESİ: Ekranda 2 öğe gösteriliyor.
                       Gelen veri 2 veya daha azsa döngünün bozulmaması için sayıyı katlıyoruz. */
                    if ($izlenimlerCount > 0 && $izlenimlerCount <= 2) {
                        // DİKKAT: Sayıyı ($izlenimlerCount) değil, koleksiyonun kendisini ($izlenimler) çoğaltıyoruz!
                        $izlenimler = $izlenimler->concat($izlenimler);
                    }
                @endphp

                <div class="swiper swiper-izlenimler">
                    <div class="swiper-wrapper">
                        @foreach($izlenimler as $izlenim)

                            <div class="swiper-slide item @if($loop->iteration%2==0) left @endif">
                                <h3 class="yorum-yapan">{{ $izlenim->name }}</h3>
                                <div class="content">
                                    <figure class="yorum-ikon">
                                        {{-- DİKKAT: $izlenimler değil, $izlenim değişkeni kullanıldı (Hata önlendi) --}}
                                        @if(empty($izlenim->image))
                                            <img src="{{ asset('images/user/index/mesaj-baloncuk.svg') }}"
                                                 alt="{{ $izlenim->name ?? 'Yorum' }}"
                                                 loading="lazy"
                                                 decoding="async">
                                        @else
                                            <img src="{{ $izlenim->image() }}"
                                                 alt="{{ $izlenim->name ?? 'Yorum' }}"
                                                 loading="lazy"
                                                 decoding="async">
                                        @endif
                                    </figure>

                                    <div class="yorum">
                                        {{ $izlenim->content }}
                                    </div>
                                </div>
                                <figure class="tirnak-ikon">
                                    <img src="{{ asset('images/user/index/cift-tirnak-kapanis.svg') }}"
                                         alt="{{$izlenim->name}}">
                                </figure>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>






    {{-- Anasayfa Verileri Bitiş --}}

@endsection
@section('extraJs')
    {{--    kaldırabilir--}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                // Tıklama olayı
                card.addEventListener('click', function (e) {
                    // Eğer butona tıklanmadıysa (tüm karta tıklandıysa)
                    // Gerçek projede burada sayfa yönlendirmesi yapılır.
                    const title = this.querySelector('h3').innerText;
                    console.log(title + " kartına tıklandı.");

                    // Tıklama animasyonu (küçülüp büyüme)
                    this.style.transform = "scale(0.98)";
                    setTimeout(() => {
                        this.style.transform = "scale(1)";
                        // Hover efekti geri gelsin diye translateY'i CSS halleder
                        this.style.removeProperty('transform');
                    }, 100);
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Tüm buton yazılarını seç
            const texts = document.querySelectorAll('.btn-text');

            texts.forEach(textElement => {
                const textContent = textElement.textContent.trim();
                textElement.innerHTML = ''; // İçeriği temizle

                // Metni harf harf böl ve span içine al
                textContent.split('').forEach((char, index) => {
                    const span = document.createElement('span');

                    // Boşluk karakteri ise özel işlem (yoksa görünmez)
                    span.innerHTML = char === ' ' ? '&nbsp;' : char;

                    // Gecikme süresini hesapla (Her harf için 30ms artar)
                    // Bu sayede harfler sırayla gelir: pıt pıt pıt...
                    span.style.transitionDelay = `${index * 35}ms`;

                    textElement.appendChild(span);
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            var swiper = new Swiper('.swiper-carousel-slider', {
                slidesPerView: 1,

                /* ÖNEMLİ: Boşlukları CSS ile verdiğimiz için bunu 0 yapıyoruz */
                spaceBetween: 0,

                loop: true,
                speed: 2500,
                autoplay: {
                    delay: 5500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            var slideCount = document.querySelectorAll(".swiper-duyurular .swiper-slide").length;

            var swiper = new Swiper(".swiper-duyurular", {
                grabCursor: true,
                centeredSlides: true,
                loop: true,
                loopedSlides: slideCount * 3,
                loopAdditionalSlides: slideCount,
                watchSlidesProgress: true,
                slidesPerView: 'auto',
                spaceBetween: 0,
                speed: 600,

                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            var swiperKarnaval = new Swiper('.swiper-karnaval-bilgileri', {
                loop: true,
                spaceBetween: 10,
                speed: 600,
                grabCursor: true,

                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },

                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },

                breakpoints: {
                    0:    { slidesPerView: 1 },
                    768:  { slidesPerView: 3 },
                    1024: {
                        slidesPerView: 4,
                        allowTouchMove: false,  // dokunarak kaydırmayı da kapat
                    },
                    1366: {
                        slidesPerView: 4,
                        allowTouchMove: false,
                    }
                }
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var swiperIzlenimler = new Swiper('.swiper-izlenimler', {
                loop: true,
                speed: 600, // Yumuşak geçiş hızı
                grabCursor: true,

                // Hata önleyiciler
                observer: true,
                observeParents: true,

                autoplay: {
                    delay: 4500, // Okuma süresi için biraz daha uzun tutulabilir
                    disableOnInteraction: false,
                },

                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },

                // Owl Responsive ayarlarının Swiper karşılığı
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                        spaceBetween: 20, // Mobilde çok boşluk olmasın
                    },
                    768: {
                        slidesPerView: 1,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 2,
                        spaceBetween: 40,
                    },
                    1366: {
                        slidesPerView: 2,
                        spaceBetween: 50, // Senin orijinal margin: 50 değerin
                    }
                }
            });
        });
    </script>

@endsection
