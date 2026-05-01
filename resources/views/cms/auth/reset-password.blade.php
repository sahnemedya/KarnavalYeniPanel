<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cms.partial.head')

<body>

<div class="login-main flex-center">
    <div class="card">
        <img src="{{asset("images/panel/site/login-image.png")}}" class="shadow-light" width="300" alt="Giriş Yap">

        <form action="{{ route('password.store') }}" class="card login-form display-flex flex-direction-col justify-start align-item-start" method="POST">
            @csrf
            <h4 class="">Şifre Sıfırla</h4>

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

{{--            Email--}}
            <input type="email" id="email" name="email" value="{{old('email')}}" required autofocus autocomplete="username" placeholder="Kullanıcı Adı">
            @if ($errors->has('email'))
                @foreach($errors->get('email') as $hata)
                    <label for="email">{{$hata}}</label>
                @endforeach

            @endif

{{--            Şifre--}}
            <input type="password" name="password" id="password" required autocomplete="new-password" placeholder="Şifre">
            @if ($errors->has('password'))
                @foreach($errors->get('password') as $hata)
                    <label for="password">{{$hata}}</label>
                @endforeach
            @endif

{{--            Şifre Tekrar--}}
            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="Şifre (Tekrar)" >
            @if ($errors->has('password_confirmation'))
                @foreach($errors->get('password_confirmation') as $hata)
                    <label for="password_confirmation">{{$hata}}</label>
                @endforeach
            @endif

            <input type="submit" name="girisYap" value="Şifremi Sıfırla">
        </form>
    </div>
</div>
@include('cms.partial.footer')
</body>
</html>


