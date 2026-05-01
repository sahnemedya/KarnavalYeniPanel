@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">İletişim Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.contacts.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="name" id="name" placeholder="Adı" required
                           @if(old("name")) value="{{old("name")}}" @endif>
                    <input type="text" name="email" id="email" placeholder="Email"
                           @if(old("email")) value="{{old("email")}}" @endif>
                    <input type="text" name="email2" id="email2" placeholder="Email 2"
                           @if(old("email2")) value="{{old("email2")}}" @endif>
                    <input type="text" name="phone" id="phone" placeholder="Telefon"
                           @if(old("phone")) value="{{old("phone")}}" @endif>
                    <input type="text" name="phone2" id="phone2" placeholder="Telefon2"
                           @if(old("phone2")) value="{{old("phone2")}}" @endif>
                    <input type="text" name="address" id="address" placeholder="Adres"
                           @if(old("address")) value="{{old("address")}}" @endif>
                    <input type="text" name="country" id="country" placeholder="Ülke"
                           @if(old("country")) value="{{old("country")}}" @else value="Türkiye" @endif>
                    <input type="text" name="city" id="city" placeholder="Şehir"
                           @if(old("city")) value="{{old("city")}}" @endif>
                    <input type="text" name="state" id="state" placeholder="Eyalet / İlçe"
                           @if(old("state")) value="{{old("state")}}" @endif>
                    <input type="text" name="person" id="person" placeholder="Yetkili Kişi"
                           @if(old("person")) value="{{old("person")}}" @endif>
                    <input type="text" name="map" id="map" placeholder="Harita Yerleştirme Linki"
                           @if(old("map")) value="{{old("map")}}" @endif>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası" min="0"
                           @if(old("hit")) value="{{old("hit")}}" @endif>

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
