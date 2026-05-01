@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-6 col-lg-4">
            <div class="card-header">Kullanıcı Rolü Oluştur</div>
            <div class="card-body">
                <form action="{{route("cms.roles.update",$role->id)}}" method="post">
                    @csrf
                    @method("PUT")
                    <input type="text" name="name" id="name" placeholder="Role Adı (Ör: Editör)" value="{{$role->name}}">
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
