@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Sertifika Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.certificate.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="name">Sertifika Adı</label>
                    <input type="text" name="name" id="name" placeholder="Sertifika Adı" required>

                    <label for="type">Sertifika Türü</label>
                    <input type="text" name="type" id="type" placeholder="Sertifika Türü">

                    <label for="image">Sertifika Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Sertifika Resmi">

                    <label for="file">Sertifika Dosyası</label>
                    <input type="file" name="file" id="file" placeholder="Sertifika Dosyası">

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası">

                    @if($languages->count()>1)
                    <label for="langId">Sertifika Dili</label>
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
