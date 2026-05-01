@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Kategori Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.popup.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="title">Pop-Up Başlığı</label>
                    <input type="text" name="title" id="title" placeholder="Pop-Up Başlığı" required>

                    <label for="image">Pop-Up Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Pop-Up Resmi">

                    <label for="mobileImage">Mobil Pop-Up Resmi</label>
                    <input type="file" name="mobile_image" id="mobileImage" placeholder="Mobil Pop-Up Resmi">
                    <label for="url">Yönlendirilecek URL</label>
                    <input type="text" name="url" id="url" placeholder="Yönlendirilecek URL">
                    <label for="langId">PopUp Dili</label>
                    @if($languages->count()>1)
                    <select name="lang_id" id="langId">
                        @foreach($languages as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @endif
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
