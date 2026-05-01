@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Kategori Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.category.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="name" id="name" placeholder="Kategori Adı" required>
                    <input type="text" name="note" id="note" placeholder="Kısa Not">
                    <input type="file" name="category_image" id="category_image" placeholder="Kategori Resmi">
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası">
                    <select name="parent_category" id="parent_category">
                        <option selected disabled hidden>Üst Kategori</option>
                        <option value="">Ana Menü</option>
                        @foreach($categories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    {{--                    Eğer anadilde ise translation of gizli olacak--}}
                    <select name="translation_of" id="translation_of"
                            title="Eğer eklediğiniz kategori çeviri sayfası ise, yani site ana dilinden farklı bir dil için ekleniyorsa hangi sayfanın çeviri olduğunu seçin.">
                        <option value="">Çeviri Değil (?)</option>
                        @foreach($categories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>

                    @if($languages->count()>1)
                    <select name="lang_id" id="lang_id">

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
