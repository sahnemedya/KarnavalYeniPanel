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
            <div class="card-header">Doktor Güncelle</div>
            <div class="card-body">
                <form action="{{route("cms.doctors.update",$doctor->id)}}" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method("PUT")
                    @if($doctor->image)
                        <figure class="form-figure">
                            <img src="{{$doctor->image()}}" id="formFigure" alt="">
                            <label onclick="removeImage()" class="btn delete-image-btn bg-error" id="removeButton">Resmi
                                Sil</label>
                        </figure>
                    @endif
                    @if($doctor->image2)
                        <figure class="form-figure">
                            <img src="{{$doctor->image2()}}" id="formFigure2" alt="">
                            <label onclick="removeImage2()" class="btn delete-image-btn bg-error" id="removeButton2">2. Resmi
                                Sil</label>
                        </figure>
                    @endif



{{--                    <label for="doctorTitle">Ünvan:</label>--}}
{{--                    <select name="doctor_title" id="doctorTitle">--}}
{{--                        <option value="Dr." @if($doctor->doctor_title == "Dr.") selected @endif >Dr. (Doktor)--}}
{{--                        </option>--}}
{{--                        <option value="Uzm. Dr." @if($doctor->doctor_title == "Uzm. Dr.") selected @endif >Uzm. Dr.--}}
{{--                            (Uzman Doktor)--}}
{{--                        </option>--}}
{{--                        <option value="Doç. Dr." @if($doctor->doctor_title == "Doç. Dr.") selected @endif >Doç. Dr.--}}
{{--                            (Doçent Doktor)--}}
{{--                        </option>--}}
{{--                        <option value="Prof. Dr." @if($doctor->doctor_title == "Prof. Dr.") selected @endif >Prof.--}}
{{--                            Dr. (Profesör Doktor)--}}
{{--                        </option>--}}
{{--                        <option value="Dyt." @if($doctor->doctor_title == "Dyt.") selected @endif >Dyt.--}}
{{--                            (Diyetisyen)--}}
{{--                        </option>--}}
{{--                        <option value="Psk." @if($doctor->doctor_title == "Psk.") selected @endif >Psk.--}}
{{--                            (Psikolog)--}}
{{--                        </option>--}}
{{--                        <option value="Uzm. Psk." @if($doctor->doctor_title == "Uzm. Psk.") selected @endif >Uzm.--}}
{{--                            Psk. (Uzman Psikolog)--}}
{{--                        </option>--}}
{{--                    </select>--}}
                    <label for="title">Doktor'un Ünvanı ve Adı Soyadı:</label>
                    <input type="text" name="title" id="title" placeholder="Ünvanı ve Adı Soyadı" value="{{$doctor->title}}">
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" placeholder="Description" value="{{$doctor->description}}">
                    <label for="slug">Sayfa Url</label>
                    <input type="text" name="slug" id="slug" placeholder="Url" value="{{ $doctor->slug }}">

                    <label for="contentText">İçerik</label>
                    <textarea name="content_text" id="contentText" cols="30"
                              rows="10">{{$doctor->content}}</textarea>
                    <br>

                    <label for="medicalUnit">Birimi:</label>
                    <select name="medical_unit" id="medicalUnit">
                        @foreach($medicalUnit->subPages as $item)
                            <option value="{{$item->id}}"
                                    @if($doctor->medical_unit == $item->id) selected @endif >{{$item->title}}</option>
                        @endforeach
                    </select>
                    <label for="medicalUnit2">2. Birimi:</label>
                    <select name="medical_unit2" id="medicalUnit2" class="form-select">
                        <option value="">Seçiniz</option>
                        @foreach($medicalUnit->subPages as $item)
                            <option value="{{$item->id}}"
                                {{ $doctor->medical_unit2 == $item->id ? 'selected' : '' }}>
                                {{$item->title}}
                            </option>
                        @endforeach
                    </select>
                    <label for="hit">Doktor Sıralaması</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası" value="{{ $doctor->hit }}">

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
        function removeImage() {
            let categoryImage = document.getElementById('formFigure');
            const removeButton = document.getElementById("removeButton");
            const removeCheckbox = document.getElementById("removeCheckbox")
            if (categoryImage.style.display === "none") {
                categoryImage.style.display = "block";
                removeButton.classList.remove("bg-success");
                removeButton.classList.add("bg-error");
                removeButton.innerHTML = "Resmi Sil";
                removeCheckbox.removeAttribute("checked");
            } else {
                categoryImage.style.display = "none";
                removeButton.classList.add("bg-success");
                removeButton.classList.remove("bg-error");
                removeButton.innerHTML = "Resmi Geri Yükle";
                removeCheckbox.setAttribute("checked", "checked");
            }
        }

        function removeImage2() {
            let categoryImage = document.getElementById('formFigure');
            const removeButton = document.getElementById("removeButton");
            const removeCheckbox = document.getElementById("removeCheckbox")
            if (categoryImage.style.display === "none") {
                categoryImage.style.display = "block";
                removeButton.classList.remove("bg-success");
                removeButton.classList.add("bg-error");
                removeButton.innerHTML = "2. Resmi Sil";
                removeCheckbox.removeAttribute("checked");
            } else {
                categoryImage.style.display = "none";
                removeButton.classList.add("bg-success");
                removeButton.classList.remove("bg-error");
                removeButton.innerHTML = "2. Resmi Geri Yükle";
                removeCheckbox.setAttribute("checked", "checked");
            }
        }
    </script>
@endsection
