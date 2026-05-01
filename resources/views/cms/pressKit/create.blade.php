@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Basın Kiti Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.press-kit.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="name">Adı</label>
                    <input type="text" name="name" id="name" placeholder="Adı" required>

                    <label for="image">Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Resmi">

                    <label for="file">Dosya</label>
                    <input type="file" name="file" id="file" placeholder="Dosya">

                    @if($languages->count()>1)
                    <label for="langId">Dili</label>
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
