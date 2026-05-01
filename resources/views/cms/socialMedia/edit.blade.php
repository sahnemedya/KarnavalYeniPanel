@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Sosyal Medya Bilgileri Ekle</div>
            <div class="card-body">
                <a onclick="deleteFunc('{{route("cms.socialMedia.destroy",$social->id)}}')"
                   class="btn bg-error margin-b-mid">Sosyal Medya Bilgilerini Kaldır</a>
                <form action="{{route("cms.socialMedia.update",$social->id)}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <label for="facebook">Facebook:</label>
                    <input type="text" name="facebook" id="facebook" placeholder="Facebook"
                           @if($social->facebook)value="{{$social->facebook}}"
                           @elseif(old("facebook")) value="{{old("facebook")}}" @endif>

                    <label for="instagram">İnstagram:</label>
                    <input type="text" name="instagram" id="instagram" placeholder="İnstagram"
                           @if($social->instagram)value="{{$social->instagram}}"
                           @elseif(old("instagram")) value="{{old("instagram")}}" @endif>

                    <label for="twitter">Twitter:</label>
                    <input type="text" name="twitter" id="twitter" placeholder="Twitter"
                           @if($social->twitter)value="{{$social->twitter}}"
                           @elseif(old("twitter")) value="{{old("twitter")}}" @endif>

                    <label for="linkedin">Linkedin:</label>
                    <input type="text" name="linkedin" id="linkedin" placeholder="Linkedin"
                           @if($social->linkedin)value="{{$social->linkedin}}"
                           @elseif(old("linkedin")) value="{{old("linkedin")}}" @endif>

                    <label for="youtube">Youtube:</label>
                    <input type="text" name="youtube" id="youtube" placeholder="Youtube"
                           @if($social->youtube)value="{{$social->youtube}}"
                           @elseif(old("youtube")) value="{{old("youtube")}}" @endif>

                    <label for="tiktok">Tiktok:</label>
                    <input type="text" name="tiktok" id="tiktok" placeholder="Tiktok"
                           @if($social->tiktok)value="{{$social->tiktok}}"
                           @elseif(old("tiktok")) value="{{old("tiktok")}}" @endif>

                    <label for="whatsapp">Whatsapp:</label>
                    <input type="text" name="whatsapp" id="whatsapp" placeholder="Whatsapp"
                           @if($social->whatsapp)value="{{$social->whatsapp}}"
                           @elseif(old("whatsapp")) value="{{old("whatsapp")}}" @endif>

                    <label for="telegram">Telegram:</label>
                    <input type="text" name="telegram" id="telegram" placeholder="Telegram"
                           @if($social->telegram)value="{{$social->telegram}}"
                           @elseif(old("telegram")) value="{{old("telegram")}}" @endif>

                    <label for="behance">Behance:</label>
                    <input type="text" name="behance" id="behance" placeholder="Behance"
                           @if($social->behance)value="{{$social->behance}}"
                           @elseif(old("behance")) value="{{old("behance")}}" @endif>

                    <label for="pinterest">Pinterest:</label>
                    <input type="text" name="pinterest" id="pinterest" placeholder="Pinterest"
                           @if($social->pinterest)value="{{$social->pinterest}}"
                           @elseif(old("pinterest")) value="{{old("pinterest")}}" @endif>

                    <label for="threads">Threads:</label>
                    <input type="text" name="threads" id="threads" placeholder="Threads"
                           @if($social->threads)value="{{$social->threads}}"
                           @elseif(old("threads")) value="{{old("threads")}}" @endif>

                    <label for="reddit">Reddit:</label>
                    <input type="text" name="reddit" id="reddit" placeholder="Reddit"
                           @if($social->reddit)value="{{$social->reddit}}"
                           @elseif(old("reddit")) value="{{old("reddit")}}" @endif>

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
@section("extraJs")
    <script>
        function deleteFunc(route) {
            alert();
            axios.delete(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (response.data.status === "success") {
                    notyf.success(response.data.message);
                    setInterval(function () {
                        window.location.href = "{{ route('cms.contacts.index') }}";
                    }, 1500)
                } else if (response.data.status === "warning") {
                    notyf.open({
                        type: "warning",
                        message: response.data.message
                    });
                } else {
                    notyf.error(response.data.message);
                }
            })
            .catch(error => {
                notyf.error(response.data.message);
            });
        }
    </script>
@endsection
