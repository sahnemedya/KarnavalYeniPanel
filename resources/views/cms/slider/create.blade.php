@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Slayt Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.slider.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="title">Slayt Başlığı</label>
                    <input type="text" name="title" id="title" placeholder="Slayt Başlığı" required>
                    <label for="description">Slayt Açıklaması</label>
                    <input type="text" name="description" id="description" placeholder="Slayt Açıklaması">
                    <label for="image">Slayt Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Slayt Resmi">
                    <label for="mobileImage">Mobil Slayt Resmi</label>
                    <input type="file" name="mobile_image" id="mobileImage" placeholder="Mobil Slayt Resmi">
                    <label for="url">Yönlendirilecek URL</label>
                    <input type="text" name="url" id="url" placeholder="Yönlendirilecek URL">
                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası">

                    @if($languages->count()>1)
                    <label for="langId">Slayt Dili</label>
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
