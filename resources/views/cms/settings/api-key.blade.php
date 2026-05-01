@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Site Ayarları</div>
            <div class="card-body">
                <form action="{{route("cms.settings.api-key.update",1)}}" method="post">
                    @csrf
                    @method("PUT")

                    <label for="youtubeChannelId">Youtube Kanal ID:</label>
                    <input type="text" name="youtube_channel_id" id="youtubeChannelId" placeholder="Youtube Kanal ID"
                           value="{{ $apiKey->youtube_channel_id }}">

                    <label for="recaptchaSiteKey">Recaptcha Site Key:</label>
                    <input type="text" name="recaptcha_site_key" id="recaptchaSiteKey" placeholder="Recaptcha Site Key"
                           value="{{ $apiKey->recaptcha_site_key }}">

                    <label for="recaptchaSecretKey">Recaptcha Secret Key:</label>
                    <input type="text" name="recaptcha_secret_key" id="recaptchaSecretKey" placeholder="Recaptcha Secret Key"
                           value="{{ $apiKey->recaptcha_secret_key }}">

                    <input type="submit" value="Kaydet">
                </form>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
