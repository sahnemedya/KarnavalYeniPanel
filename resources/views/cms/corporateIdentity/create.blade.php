@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Kurumsal Kimlik Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.corporateIdentity.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="name">Kurumsal Kimlik Adı</label>
                    <input type="text" name="name" id="name" placeholder="Kurumsal Kimlik Adı" required>

                    <label for="file">Kurumsal Kimlik Dosyası</label>
                    <small class="form-text text-muted">Lütfen PDF, DOCX gibi belgeleri yükleyin.</small>
                    <input type="file" name="file" id="file" placeholder="Kurumsal Kimlik Dosyası">

                    <label for="image">Kurumsal Kimlik Resmi</label>
                    <small class="form-text text-muted">Lütfen PNG, JPG gibi görsel dosyaları yükleyin.</small>

                    <input type="file" name="image" id="image" placeholder="Kurumsal Kimlik Resmi">

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası">

                    @if($languages->count()>1)
                        <label for="langId">Kurumsal Kimlik Dili</label>
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
