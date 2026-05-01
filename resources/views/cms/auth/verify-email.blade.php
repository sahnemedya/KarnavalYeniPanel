<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cms.partial.head')
@include('cms.partial.header')
<body>

<div class="login-main flex-center">
    <div class="card">
        <img src="{{asset("images/panel/site/login-image.png")}}" class="shadow-light" width="300" alt="Giriş Yap">

        <form action="{{ route('cms.verification.send') }}" class="card login-form display-flex flex-direction-col justify-start align-item-start" method="POST">
            @csrf
            <h4 class="">Hesabımı Doğrula</h4>
            <input type="submit" name="girisYap" value="Hesabımı Doğrula">
        </form>
    </div>
</div>
@include('cms.partial.footer')
</body>
</html>


