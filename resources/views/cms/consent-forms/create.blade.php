@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Onam Formu Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.consent-forms.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="title">Onam Formu Adı</label>
                    <input type="text" name="title" id="title" placeholder="Onam Formu Adı" required>

                    <label for="tr_file">Onam Formu TR Dosya</label>
                    <input type="file" name="tr_file" id="tr_file" placeholder="Onam Formu TR Dosya">

                    <label for="tr_file">Onam Formu EN Dosya</label>
                    <input type="file" name="en_file" id="en_file" placeholder="Onam Formu EN Dosya">

                    <label for="page">Bağlı Olduğu Sayfa</label>
                    <label for="page">Bağlı Olduğu Sayfa</label>
                    <select name="page_id" id="page">
                        <option value="">Kayıtlı Sayfa Yok</option>
                        @foreach($pages as $item)
                            <option value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>

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
