@extends("cms.partial.layout")
@section("extraCss")
    <link rel="stylesheet" href="{{asset("plugins/ckeditor/skins/moono/editor.css")}}">
    <script src="{{asset("plugins/ckeditor/lang/tr.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/styles.js")}}"></script>
    <script src="{{asset("plugins/ckeditor/ckeditor.js")}}"></script>
@endsection
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Yorumcu Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.comments.store")}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <label for="name">Yorumcu Adı Soyadı</label>
                    <input type="text" name="name" id="name" placeholder="Yorumcu Adı Soyadı" required>

                    <label for="contentText">Yorum</label>
                    <textarea name="content_text" id="contentText" cols="30" rows="10"></textarea>

                    <label for="image">Yorumcu Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Yorumcu Resmi">

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası">


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
@section("extraJs")
    <script src="{{asset("plugins/ckeditor/config.js")}}"></script>
    <script>
        let ckeditor = document.getElementById("contentText");
        if (ckeditor && typeof CKEDITOR !== "undefined") {
            CKEDITOR.replace('contentText', {
                filebrowserWindowWidth: '1000',
                filebrowserWindowHeight: '700'
            });
        }
    </script>
@endsection
