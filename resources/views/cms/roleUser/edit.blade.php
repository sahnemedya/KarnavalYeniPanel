@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-6 col-lg-4">
            <div class="card-header">Kullanıcıya Yetki Güncelle</div>
            <div class="card-body">
                <form action="{{route("cms.role-user.update",$roleUser->id)}}" method="post">
                    @csrf
                    @method("PUT")
                    <select name="user_id" id="userId" readonly>
                        <option value="{{$roleUser->user->id}}">{{$roleUser->user->name}}</option>
                    </select>
                    <select name="role_id" id="roleId">
                        @foreach($roles as $item)
                            <option value="{{$item->id}}" @if($roleUser->role_id == $item->id) selected
                                    disabled @endif>{{$item->name}}</option>
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
