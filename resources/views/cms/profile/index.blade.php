@extends("cms.partial.layout")
@section("content")

    {{--    Hesap Doğrulama--}}

    @if (session('status') == 'verification-link-sent')
        <div class="card">
            <div class="card-header text-success">Hesabınızı Aktif Edilmiştir.</div>
        </div>
    @endif

    @if (!$user->hasVerifiedEmail())
        <div class="card w-50">
            <div class="card-header text-center">
                Hesabınızı Aktifleştirin
            </div>
            <div class="card-body">
                Hesabınızı aktifleştirmek için aşağıdaki butona tıklayın. Ardından mail adresinize gelecek olan maili
                onaylayın.

                <form id="send-verification" method="post" action="{{ route('cms.verification.send') }}">
                    @csrf
                    <input type="submit" class="w-unset bg-error" value="Aktifleştir">
                </form>
            </div>
        </div>

    @else
        <div class="flex-center w-50">
            <input type="submit" class="w-unset bg-success margin-min" disabled value="Hesabınız Aktiftir"
                   onclick="console.log('');">
        </div>
    @endif
    {{--    Hesap Bilgilerini Güncelleme--}}
    <div class="card w-50">
        <div class="card-header text-center">
            Profil Bilgilerini Güncelle
        </div>
        <div class="card-body">
            <form method="post" class="w-100" action="{{ route('cms.profile.update') }}">
                @csrf
                @method('patch')
                <input type="text" class="" name="name" id="name" value="{{old('name', $user->name)}}"
                       placeholder="{{ __('Ad Soyad') }}" required autofocus>
                <input type="email" class="" id="email" name="email" value="{{old('email', $user->email)}}"
                       placeholder="{{ __('Kullanıcı Adı') }}" required autofocus>
                <input type="submit" class="" name="girisYap" value="Bilgilerimi Güncelle">

            </form>
        </div>
    </div>
    {{--    Hesap Şifresini Güncelleme--}}
    <div class="card w-50">
        <div class="card-header text-center">
            Şifre Güncelle
        </div>
        <div class="card-body">
            <form method="post" class="w-100" action="{{ route('cms.password.update') }}">
                @csrf
                @method('put')

                <input type="password" name="current_password" id="password" required autocomplete="current-password" placeholder="Şuanki Şifreniz">
                <input type="password" name="password" id="password" required autocomplete="new-password" placeholder="Yeni Şifre">
                <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="Yeni Şifre (Tekrar)" >
                <input type="submit" class="" name="girisYap" value="Şifre Değiştir">
            </form>
            @if (session('status') === 'password-updated')
                <p class="text-success margin-min">Şifreniz Değiştirilmiştir.</p>
            @endif
        </div>
    </div>

@endsection
