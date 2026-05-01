<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cms.partial.head')
<body>

<div class="login-main flex-center">
    <div class="card">
        <img src="{{asset("images/panel/site/login-image.png")}}" class="shadow-light" width="300" alt="Giriş Yap">

        <form action="{{ route('password.confirm') }}"
              class="card login-form display-flex flex-direction-col justify-start align-item-start" method="POST">
            @csrf
            <h4 class="">Şifreni Onayla</h4>

            <input type="password" id="password" name="password" value="{{old('email')}}" required autofocus
                   autocomplete="password" placeholder="Şifre">
            @if ($errors->has('password'))
                <label for="password">{{$errors->get('password')}}</label>
            @endif
            <input type="submit" name="girisYap" value="Şifremi Sıfırla">
        </form>
    </div>
</div>
@include('cms.partial.footer')
</body>
</html>


