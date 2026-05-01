<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cms.partial.head')
<body>

<div class="login-main flex-center">
    <div class="card">
        <img src="{{asset("images/panel/site/login-image.png")}}" class="shadow-light" width="300" alt="Giriş Yap">

        <form action="{{ route('cms.login') }}" class="card login-form display-flex flex-direction-col justify-start align-item-start" method="POST">
            @csrf
            <h4 class="">Giriş Yap</h4>
            <input type="email" id="email" name="email" value="{{old('email')}}" required autofocus
                   autocomplete="username" placeholder="Kullanıcı Adı (tahaercan2749@gmail.com)">
            @if ($errors->has('email'))
            @foreach($errors->get('email') as $error)
                <label for="email">{{$error}}</label>
                @endforeach
            @endif
            <input type="password" id="password" name="password" required autocomplete="current-password"
                   placeholder="Şifre (sahnemedya)">
            @if ($errors->has('password'))
                @foreach($errors->get('password') as $error)
                    <label for="email">{{$error}}</label>
                @endforeach
            @endif

            <a href="{{route("cms.password.email")}}">Parolamı Unuttum</a>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('Parolamı Unuttum') }}</a>
            @endif
            <input type="submit" name="girisYap" value="Bağlan">
        </form>
    </div>
</div>
@include('cms.partial.footer')
</body>
</html>


