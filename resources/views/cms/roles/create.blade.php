@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-6 col-lg-4">
            <div class="card-header">Kullanıcı Rolü Oluştur</div>
            <div class="card-body">
                <form action="{{route("cms.roles.store")}}" method="post">
                    @csrf
                    <input type="text" name="name" id="name" placeholder="Role Adı (Ör: Editör)">
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
