@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Seo Güncelle</div>
            <div class="card-body">
                <form action="{{route("cms.seos.update",$seo->id)}}" method="post">
                    @csrf
                    @method("PUT")
                    <input type="text" name="title" id="name" placeholder="Seo Title" value="{{$seo->title}}">

                    <input type="text" name="description" id="name" placeholder="Seo Description"
                           value="{{$seo->description}}">
                    <input type="text" name="geo_title" id="name" placeholder="Geo Title" value="{{$seo->geo_title}}">
                    <input type="text" name="geo_description" id="name" placeholder="Geo Description"
                           value="{{$seo->geo_description}}">
                    <select name="canonical" id="canonical">
                        <option value="">Canonical Yok</option>
                        @foreach($pages as $item)
                            <option value="{{$item->id}}" @if($seo->canonical == $item->id) selected @endif>{{$item->title}}</option>
                        @endforeach
                    </select>
                    <select name="page_id" id="page_id">
                        <option value="">Seo Ait Olduğu Sayfa</option>
                        @foreach($pages as $item)
                            <option value="{{$item->id}}" @if($seo->page_id == $item->id) selected @endif>{{$item->title}}</option>
                        @endforeach
                    </select>
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
