@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Sosyal Medya Bilgileri Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.socialMedia.store",$contactId)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="facebook">Facebook:</label>
                    <input type="text" name="facebook" id="facebook" placeholder="Facebook"
                           @if(old("facebook")) value="{{old("facebook")}}" @endif>

                    <label for="instagram">İnstagram:</label>
                    <input type="text" name="instagram" id="instagram" placeholder="İnstagram"
                           @if(old("instagram")) value="{{old("instagram")}}" @endif>

                    <label for="twitter">Twitter:</label>
                    <input type="text" name="twitter" id="twitter" placeholder="Twitter"
                           @if(old("twitter")) value="{{old("twitter")}}" @endif>

                    <label for="linkedin">Linkedin:</label>
                    <input type="text" name="linkedin" id="linkedin" placeholder="Linkedin"
                           @if(old("linkedin")) value="{{old("linkedin")}}" @endif>

                    <label for="youtube">Youtube:</label>
                    <input type="text" name="youtube" id="youtube" placeholder="Youtube"
                           @if(old("youtube")) value="{{old("youtube")}}" @endif>

                    <label for="tiktok">Tiktok:</label>
                    <input type="text" name="tiktok" id="tiktok" placeholder="Tiktok"
                           @if(old("tiktok")) value="{{old("tiktok")}}" @endif>

                    <label for="whatsapp">Whatsapp:</label>
                    <input type="text" name="whatsapp" id="whatsapp" placeholder="Whatsapp"
                           @if(old("whatsapp")) value="{{old("whatsapp")}}" @endif>

                    <label for="telegram">Telegram:</label>
                    <input type="text" name="telegram" id="telegram" placeholder="Telegram"
                           @if(old("telegram")) value="{{old("telegram")}}" @endif>

                    <label for="behance">Behance:</label>
                    <input type="text" name="behance" id="behance" placeholder="Behance"
                           @if(old("behance")) value="{{old("behance")}}" @endif>

                    <label for="pinterest">Pinterest:</label>
                    <input type="text" name="pinterest" id="pinterest" placeholder="Pinterest"
                           @if(old("pinterest")) value="{{old("pinterest")}}" @endif>

                    <label for="threads">Threads:</label>
                    <input type="text" name="threads" id="threads" placeholder="Threads"
                           @if(old("threads")) value="{{old("threads")}}" @endif>

                    <label for="reddit">Reddit:</label>
                    <input type="text" name="reddit" id="reddit" placeholder="Reddit"
                           @if(old("reddit")) value="{{old("reddit")}}" @endif>

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
