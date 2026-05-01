@include('user.partial.head')
@include('user.partial.header')
{{--Geçerli sayfanın anasayfa olup olmadığını kontrol ediyor eğer ana sayfa ise breadcrumb yuklenmiyor--}}
@if(Route::current()->getName() != 'home' && Route::current()->getName() != 'urunAra')
    @include('user.partial.breadcrumb')
@endif

@include('sweetalert::alert')
@yield('content')
@include('user.partial.footer')
