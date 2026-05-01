<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('cms.partial.head')
<body>

<div class="login-main flex-center">
    <div class="card">
        <img src="{{asset("images/panel/site/login-image.png")}}" class="shadow-light" width="300" alt="Giriş Yap">

        <form action="{{route("cms.password.email")}}" class="card login-form display-flex flex-direction-col justify-start align-item-start" method="POST">
            @csrf
            <h4 class="">Parolamı Unuttum</h4>
            <input type="email" id="email" name="email" value="{{old('email')}}" required autofocus
                   autocomplete="username" placeholder="Kullanıcı Adı">
            @if ($errors->has('email'))
                @foreach($errors->get('email') as $hata)
                    <label for="email">{{$hata}}</label>
                @endforeach

            @endif
            <input type="submit" name="girisYap" value="Şifremi Sıfırla">
        </form>
    </div>
</div>
@include('cms.partial.footer')
</body>
</html>


