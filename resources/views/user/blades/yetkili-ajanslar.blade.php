@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>


                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif

                <h2>Ulusal Sponsorluk Satışı ve Pazarlaması'ndan Sorumlu Yetkili Ajans</h2>
                <div class="yetkili-ajanslar">
                    <div class="ajans ulusal">
                        <figure><img src="https://www.nisandaadanada.com/images/user/yetkili-ajanslar/on-dokuz.jpg"
                                     alt="On Dokuz"></figure>
                        <div class="bilgiler">
                            <a href="tel:02163180319" class="tel1"><i class="las la-phone-volume"></i> 0 216 318 03
                                19</a> <a href="tel:05322978601" class="tel2"><i class="las la-phone-volume"></i> 0 532
                                297 86 01</a> <a href="mailto:feride@ondokuz.org" class="mail2"> <i
                                    class="las la-envelope"></i> feride@ondokuz.org</a> <a
                                href="mailto:sponsorluk@nisandaadanada.com" class="mail2"> <i
                                    class="las la-envelope"></i> sponsorluk@nisandaadanada.com</a></div>
                    </div>
                </div>

                <h2>Bölgesel Sponsorluk Satışı ve Pazarlaması'ndan Sorumlu Yetkili Ajanslar</h2>

                <div class="yetkili-ajanslar">
                    <div class="ajans bolgesel">
                        <figure><img src="https://www.nisandaadanada.com/images/user/yetkili-ajanslar/paper-n-party.jpg"
                                     alt="Paper N Party"></figure>
                        <div class="bilgiler">
                            <a href="tel:05412881231" class="tel1"><i class="las la-phone-volume"></i> 0 541 288 12
                                31</a> <a href="mailto:pnp@nisandaadanada.com" class="mail2"> <i
                                    class="las la-envelope"></i> pnp@nisandaadanada.com</a></div>
                    </div>
                    <div class="ajans bolgesel">
                        <figure><img src="https://www.nisandaadanada.com/images/user/yetkili-ajanslar/sahne-medya.jpg"
                                     alt="Sahne Medya"></figure>
                        <div class="bilgiler">
                            <a href="tel:03222621505" class="tel1"><i class="las la-phone-volume"></i> 0 322 262 15
                                05</a> <a href="tel:05442621505" class="tel2"><i class="las la-phone-volume"></i> 0 544
                                262 15 05</a> <a href="mailto:sahnemedya@nisandaadanada.com" class="mail2"> <i
                                    class="las la-envelope"></i> sahnemedya@nisandaadanada.com</a></div>
                    </div>
                    <div class="ajans bolgesel">
                        <figure><img
                                src="https://www.nisandaadanada.com/images/user/yetkili-ajanslar/sosyal-fabrika.jpg"
                                alt="Sosyal Fabrika"></figure>
                        <div class="bilgiler">
                            <a href="tel:05052452674" class="tel1"><i class="las la-phone-volume"></i> 0 505 245 26
                                74</a> <a href="mailto:sosyalfabrika@nisandaadanada.com" class="mail2"> <i
                                    class="las la-envelope"></i> sosyalfabrika@nisandaadanada.com</a></div>
                    </div>
                </div>
            </div>


        </div>
    </section>

@endsection








