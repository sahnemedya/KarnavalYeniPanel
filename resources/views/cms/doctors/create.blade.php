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
            <div class="card-header">Doktor Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.doctors.store")}}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{--                    <label for="doctorTitle">Ünvan:</label>--}}
                    {{--                    <select name="doctor_title" id="doctorTitle">--}}
                    {{--                        <option value="Dr.">Dr. (Doktor)</option>--}}
                    {{--                        <option value="Uzm. Dr.">Uzm. Dr. (Uzman Doktor)</option>--}}
                    {{--                        <option value="Prof. Dr.">Prof. Dr. (Profesör Doktor)</option>--}}
                    {{--                        <option value="Doç. Dr." >Doç. Dr. (Doçent Doktor)</option>--}}
                    {{--                        <option value="Op. Dr." selected>Op. Dr. (Operatör Doktor)</option>--}}
                    {{--                        <option value="Dyt.">Dyt. (Diyetisyen)</option>--}}
                    {{--                        <option value="Psk.">Psk. (Psikolog)</option>--}}
                    {{--                        <option value="Uzm. Psk.">Uzm. Psk. (Uzman Psikolog)</option>--}}
                    {{--                    </select>--}}
                    <label for="title">Doktor'un Ünvanı ve Adı Soyadı:</label>
                    <input type="text" name="title" id="title" placeholder="Ünvanı ve Adı Soyadı">
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" placeholder="Description">
                    <label for="slug">Sayfa Url</label>
                    <input type="text" name="slug" id="slug" placeholder="Url">

                    <label for="contentText">İçerik</label>
                    <textarea name="content_text" id="contentText" placeholder="İçerik"></textarea>


                    <label for="medicalUnit">Birimi:</label>

                    <select name="medical_unit" id="medicalUnit">
                        @foreach($medicalUnit->subPages as $item)
                            <option value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>
                    <label for="medicalUnit2">2. Birimi:</label>
                    <select name="medical_unit2" id="medicalUnit2">
                        <option value="">Seçiniz</option>
                        @foreach($medicalUnit->subPages as $item)
                            <option value="{{$item->id}}">{{$item->title}}</option>
                        @endforeach
                    </select>

                    <label for="image">Doktor Resmi:</label>
                    <input type="file" name="image" id="image">
                    <label for="image2">Doktor Resmi 2:</label>
                    <input type="file" name="image2" id="image2">



                    <label for="hit">Doktor Sıralaması</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası">

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

        document.querySelector('form').addEventListener('submit', function () {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function () {
                    const title = this.value;

                    if (title.length > 0) {
                        axios.post('{{ route("cms.slug.maker") }}', {text: title}, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(function (response) {
                            slugInput.value = response.data.slug;
                        }).catch(function (error) {
                            console.error("Slug oluşturulamadı", error);
                        });
                    } else {
                        slugInput.value = '';
                    }
                });
            } else {
                console.warn("#title veya #slug inputu bulunamadı.");
            }
        });
    </script>
@endsection
