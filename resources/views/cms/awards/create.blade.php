@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Ödül Ekle</div>
            <div class="card-body">
                <form action="{{ route('cms.awards.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <label for="name">Ödül Adı</label>
                    <input type="text" name="name" id="name" placeholder="Ödül Adı" value="{{ old('name') }}" required>

                    <label for="image">Ödül Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Ödül Resmi">

                    <label for="prize_date">Ödül Tarihi</label>
                    <input type="date" name="prize_date" id="prize_date" value="{{ old('prize_date') }}">

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası" value="{{ old('hit') }}">

                    <label for="published" style="display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="published" id="published" value="1" {{ old('published') ? 'checked' : '' }}>
                        Yayınla
                    </label>

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
