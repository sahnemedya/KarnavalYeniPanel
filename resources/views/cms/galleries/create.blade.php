@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Galeri Ekle</div>
            <div class="card-body">

                <label for="page_id">Galeri Eklenecek Sayfa:</label>
                <select name="page_id" id="page_id_select">
                    <option value="{{$page->id}}" selected>{{$page->title}}</option>

                </select>
                <form action="{{route("cms.gallery.store")}}" method="post" enctype="multipart/form-data"
                      class="dropzone" id="dropzone">
                    @csrf
                    <input type="hidden" name="page_id" id="page_id_input">
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
    <script>
        const select = document.getElementById('page_id_select');
        const input = document.getElementById('page_id_input');

        window.addEventListener('DOMContentLoaded', () => {
            input.value = select.value;
        });

        select.addEventListener('change', function () {
            input.value = this.value;
        });


    </script>
@endsection
