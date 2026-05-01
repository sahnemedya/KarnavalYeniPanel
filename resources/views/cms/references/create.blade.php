@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Referans Ekle</span>
                <a href="{{ route('cms.references.bulk-create') }}" class="btn btn-sm btn-primary">
                    Toplu Ekle
                </a>
            </div>
            <div class="card-body">
                <form action="{{route("cms.references.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="sezonId" class="form-label" style="display: block; margin-bottom: 8px;">Karnaval
                        Sezonu:</label>
                    <select name="sezon_id" id="sezonId" title="Hangi Karnaval Sezonuna ait ise o seçilmeli.">
                        <option value="">Tüm Sezonlar</option>
                        @foreach($karnavalSezonlari as $item)
                            <option value="{{$item->id}}">{{$item->karnaval_yili}}</option>
                        @endforeach
                    </select>
                    <label for="name">Referans Adı</label>
                    <input type="text" name="name" id="name" placeholder="Referans Adı" required>


                    <label for="type_id">Referans Türü</label>
                    <select name="type_id" id="type_id">
                        <option value="">Tümü</option>
                        @foreach($refaceTypes as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>


                    <label for="image">Referans Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Referans Resmi">

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası">

                    <label for="url">Yönlendirilecek URL</label>
                    <input type="text" name="url" id="url" placeholder="Yönlendirilecek URL">
@if($languages->count()>1)
                    <label for="langId">Dil</label>
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
