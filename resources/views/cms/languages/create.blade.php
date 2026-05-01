@extends("cms.partial.layout")
@section("content")
<div class="row">
    <div class="card col-sm-12 col-md-12 col-lg-6">
        <div class="card-header">Dil Seçeneği Ekle</div>
        <div class="card-body">
            <form action="{{route("cms.languages.store")}}" method="post">
                @csrf
                <input type="text" name="name" id="name" placeholder="Dil Adı">
                <input type="text" name="code" id="code" placeholder="Dil Kodu">
                <input type="submit" value="Kaydet">
            </form>
        </div>
    </div>
</div>
@endsection
