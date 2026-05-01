<!doctype html>
<html lang="tr">
<head>

    @if(isset($siteSetting) && $siteSetting !== null && $siteSetting->head_code)
        {!! $siteSetting->head_code !!}
    @endif

    {{-- Temel Meta Tags --}}
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    {!! $schemaScriptsHtml ?? '' !!}
    @stack('schema')

    <link rel="stylesheet"
          href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('css/user/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/jquery.mmenu.all.css')}}">

    @vite(['resources/css/style.scss'])
    <link rel="stylesheet" href="{{asset('css/user/table.css')}}">
    <link href="{{asset('lightbox/dist/css/lightbox.css')}}" rel="stylesheet">
    <script src="{{asset('lightbox/dist/js/lightbox.js')}}"></script>

    {{-- Google Translate --}}
    <style>
        body {
            top: 0 !important;
        }

        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }

        .goog-logo-link, .goog-te-gadget-icon {
            display: none !important;
        }

        #google_translate_element {
            position: absolute;
            top: -9999px;
            left: -9999px;
            height: 0;
            overflow: hidden;
        }
    </style>

    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'tr',
                includedLanguages: 'tr,en,de,fr,ru,ar',
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>

    @php
        $currentPage = null;
        $currentSlug = request()->segment(1) ?: 'anasayfa';
        if($currentSlug && $currentSlug != 'anasayfa') {
            $currentPage = \App\Models\Page::with(['seo', 'blade'])->where('slug', $currentSlug)->first();
        }
    @endphp

    @if(isset($currentPage) && $currentPage && isset($currentPage->extraCss))
        {!! $currentPage->extraCss !!}
    @endif
    @yield('extraCss')

    {{-- SEO Değişkenleri (Orijinal Kodun) --}}
    @php
        $TITLE = '';
        $DESCRIPTION = '';
        $CANNONICAL = url()->current();
        $IMAGE = '';
        $KEYWORDS = '';

        $isHomePage = (url()->current() == env('APP_URL')) || (request()->segment(1) == '' || request()->segment(1) == 'anasayfa');
        $isDoctorPage = isset($doktor) && !isset($doktorlar);

        if($isHomePage) {
            $TITLE = (isset($siteSetting->seo_title) && $siteSetting->seo_title ? $siteSetting->seo_title : $siteSetting->site_name) . (isset($siteSetting->site_name) ? ' | ' . $siteSetting->site_name : '');
            $DESCRIPTION = isset($siteSetting->description) ? $siteSetting->description : '';
            $IMAGE = env('APP_URL')."/images/site/".(isset($siteSetting->logo) ? $siteSetting->logo : '');
            $KEYWORDS = isset($siteSetting->seo_keywords) ? $siteSetting->seo_keywords : '';
        } elseif ($isDoctorPage) {
            $TITLE = $doktor->title . (isset($siteSetting->site_name) ? ' | ' . $siteSetting->site_name : '');
            if (!empty($doktor->description)) {
                $DESCRIPTION = $doktor->description;
            } else {
                $DESCRIPTION = \Illuminate\Support\Str::limit(strip_tags($doktor->content), 155);
            }
            if ($doktor->image()) {
                $IMAGE = $doktor->image();
            } else {
                $IMAGE = env('APP_URL')."/images/site/".(isset($siteSetting->logo) ? $siteSetting->logo : '');
            }
        } else {
            if($currentPage) {
                if($currentPage->seo) {
                    $TITLE = ($currentPage->seo->title ? $currentPage->seo->title : $currentPage->title) . (isset($siteSetting->site_name) ? ' | ' . $siteSetting->site_name : '');
                    $DESCRIPTION = $currentPage->seo->description ?: '';
                } else {
                    $TITLE = $currentPage->title . (isset($siteSetting->site_name) ? ' | ' . $siteSetting->site_name : '');
                    if (!empty($currentPage->description)) {
                         $DESCRIPTION = $currentPage->description;
                    } elseif (!empty($currentPage->content)) {
                        $DESCRIPTION = \Illuminate\Support\Str::limit(strip_tags($currentPage->content), 155);
                    }
                }
                if(method_exists($currentPage, 'image') && $currentPage->image()) {
                    $IMAGE = $currentPage->image();
                } elseif($currentPage->image) {
                     $IMAGE = env('APP_URL')."/images/user/sayfalar/".$currentPage->image;
                } else {
                    $IMAGE = env('APP_URL')."/images/site/".(isset($siteSetting->logo) ? $siteSetting->logo : '');
                }
            } else {
                $TITLE = '404 - Sayfa Bulunamadı' . (isset($siteSetting->site_name) ? ' | ' . $siteSetting->site_name : '');
                $DESCRIPTION = 'Aradığınız sayfa bulunamadı';
                $IMAGE = env('APP_URL')."/images/site/".(isset($siteSetting->logo) ? $siteSetting->logo : '');
            }
        }
    @endphp

    <title>{{ $TITLE }}</title>
    <meta name="description" content="{{ $DESCRIPTION }}">
    @if($KEYWORDS)
        <meta name="keywords" content="{{ $KEYWORDS }}">
    @endif
    <meta name="robots" content="index,follow">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $TITLE }}">
    <meta property="og:description" content="{{ $DESCRIPTION }}">
    <meta property="og:url" content="{{ $CANNONICAL }}">
    <meta property="og:site_name" content="{{ isset($siteSetting->site_name) ? $siteSetting->site_name : '' }}">
    @if($IMAGE)
        <meta property="og:image:url" content="{{ $IMAGE }}">
        <meta property="og:image:secure_url" content="{{ $IMAGE }}">
        <meta property="og:image:width" content="320">
        <meta property="og:image:height" content="200">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description" content="{{ $DESCRIPTION }}">
    <meta name="twitter:title" content="{{ $TITLE }}">
    @if($IMAGE)
        <meta name="twitter:image" content="{{ $IMAGE }}">
    @endif
    <link rel="canonical" href="{{ $CANNONICAL }}"/>
    @if(isset($siteSetting->favicon))
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/site/'.$siteSetting->favicon) }}">
    @endif

    {{-- Hreflangs --}}
    @if(isset($diller) && $diller != NULL)
        @foreach($diller as $dil)
            @if($isHomePage)
                <link rel="alternate" hreflang="{{$dil->code}}" href="{{env('APP_URL')}}/{{$dil->code}}"/>
            @else
                @if(isset($pagesninTumCevirileri) && $pagesninTumCevirileri != NULL)
                    @foreach($pagesninTumCevirileri as $ceviri)
                        @if(isset($ceviri->dil_id, $ceviri->slug) && $ceviri->dil_id == $dil->id)
                            <link rel="alternate" hreflang="{{$dil->code}}"
                                  href="{{env('APP_URL')}}/{{$ceviri->slug}}"/>
                        @endif
                    @endforeach
                @endif
            @endif
        @endforeach
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://kit.fontawesome.com/8687eee5bc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css"
          xintegrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Ortadoğu Hastanesi">

    <link rel="preload" href="{{ asset('css/user/owl.carousel.min.css') }}" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap"
          as="style">
</head>
<body>
@if(isset($siteSetting->body_ek) && $siteSetting->body_ek)
    {!! $siteSetting->body_ek !!}
@endif
