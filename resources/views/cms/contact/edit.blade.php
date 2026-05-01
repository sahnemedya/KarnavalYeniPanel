@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header"><span class="text-warning">{{$contact->name}}</span> İletişim Bilgisi Güncelle</div>
            <div class="card-body">
                <form action="{{route("cms.contacts.update",$contact->id)}}" method="post">
                    @csrf
                    @method("PUT")
                    <label for="name">Adı:</label>
                    <input type="text" name="name" id="name" placeholder="Adı" required
                           @if(old("name")) value="{{old("name")}}" @else value="{{$contact->name}}" @endif>
                    <label for="email">E-mail:</label>
                    <input type="text" name="email" id="email" placeholder="E-mail"
                           @if(old("email")) value="{{old("email")}}" @else value="{{$contact->email}}" @endif>
                    <label for="email2">E-mail 2:</label>
                    <input type="text" name="email2" id="email2" placeholder="E-mail 2"
                           @if(old("email2")) value="{{old("email2")}}" @else value="{{$contact->email2}}" @endif>
                    <label for="phone">Telefon:</label>
                    <input type="text" name="phone" id="phone" placeholder="Telefon"
                           @if(old("phone")) value="{{old("phone")}}" @else value="{{$contact->phone}}" @endif>
                    <label for="phone2">Telefon 2:</label>
                    <input type="text" name="phone2" id="phone2" placeholder="Telefon2"
                           @if(old("phone2")) value="{{old("phone2")}}" @else value="{{$contact->phone2}}" @endif>
                    <label for="address">Adres:</label>
                    <input type="text" name="address" id="address" placeholder="Adres"
                           @if(old("address")) value="{{old("address")}}"
                           @else value="{{$contact->address}}" @endif>
                    <label for="country">Ülke:</label>
                    <input type="text" name="country" id="country" placeholder="Ülke"
                           @if(old("country")) value="{{old("country")}}"
                           @else value="{{$contact->country}}" @endif>
                    <label for="city">Şehir:</label>
                    <input type="text" name="city" id="city" placeholder="Şehir"
                           @if(old("city")) value="{{old("city")}}" @else value="{{$contact->city}}" @endif>
                    <label for="state">Eyalet / İlçe:</label>
                    <input type="text" name="state" id="state" placeholder="Eyalet / İlçe"
                           @if(old("state")) value="{{old("state")}}" @else value="{{$contact->state}}" @endif>
                    <label for="person">Yetkili Kişi:</label>
                    <input type="text" name="person" id="person" placeholder="Yetkili Kişi"
                           @if(old("person")) value="{{old("person")}}" @else value="{{$contact->person}}" @endif>
                    <label for="map">Harita Yerleştirme Linki:</label>
                    <input type="text" name="map" id="map" placeholder="Harita Yerleştirme Linki"
                           @if(old("map")) value="{{old("map")}}" @else value="{{$contact->map}}" @endif>
                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası" min="0"
                           @if(old("hit")) value="{{old("hit")}}" @else value="{{$contact->hit}}" @endif>

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
