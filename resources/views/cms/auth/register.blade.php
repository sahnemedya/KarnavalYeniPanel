<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cms.partial.head')
@include('cms.partial.header')
<body>

<div class="login-main flex-center">
    <div class="card">
        <img src="{{asset("images/panel/site/login-image.png")}}" class="shadow-light" width="300" alt="Giriş Yap">

        <form action="" class="card login-form display-flex flex-direction-col justify-start align-item-start" method="POST">
            @csrf
            <h4 class="">Kullanıcı Kayıt</h4>

{{--            Ad Soyad--}}
            <input type="text" name="name" id="name" value="{{old('name')}}" placeholder="{{ __('Ad Soyad') }}" required autofocus>
            @if ($errors->has('name'))
                <label for="name">{{$errors->get('name')}}</label>
            @endif

{{--            Email--}}
            <input type="email" id="email" name="email" value="{{old('email')}}" placeholder="{{ __('Kullanıcı Adı') }}" required autofocus>
            @if ($errors->has('email'))
                <label for="email">{{$errors->get('email')}}</label>
            @endif

{{--            Şifre--}}
            <input type="password" id="password" name="password" required autocomplete="current-password"
                   placeholder="Şifre">
            @if ($errors->has('password'))
                <label for="password">{{$errors->get('password')}}</label>
            @endif

            {{--            Şifre Tekrar--}}
            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Şifre (Tekrar)') }}" >
            @if ($errors->has('password_confirmation'))
                <label for="email">{{$errors->get('password_confirmation')}}</label>
            @endif

            <a href="{{route('cms.login')}}">{{ __('Zaten Hesabım Var') }}</a>

            <input type="submit" name="girisYap" value="Bağlan">
        </form>
    </div>
</div>
@include('cms.partial.footer')
</body>
</html>


