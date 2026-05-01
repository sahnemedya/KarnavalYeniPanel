<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cms.partial.head')
<body>
@include('cms.partial.header')
<main>
    @yield('content')
</main>
@include('cms.partial.footer')
</body>
</html>



