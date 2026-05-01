@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Slayt Ekle</div>
            <div class="card-body">
                <form action="{{route("cms.slider.update",$slider->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")

                    @if($slider->image)
                        <figure class="form-figure">
                            <img src="{{$slider->getImagePath()}}" id="formFigure" alt="">
                            <label onclick="removeImage()" class="btn delete-image-btn bg-error" id="removeButton">Resmi
                                Sil</label>
                        </figure>
                    @endif
                    <input type="checkbox" style="display: none" name="removeImage" id="removeCheckbox" id=""
                           value="1">

                    @if($slider->mobile_image)
                        <figure class="form-figure">
                            <img src="{{$slider->getMobileImagePath()}}" id="formFigure2" alt="">
                            <label onclick="removeMobileImage()" class="btn delete-image-btn bg-error" id="removeButton2">Mobil Resmi
                                Sil</label>
                        </figure>
                    @endif
                    <input type="checkbox" style="display: none" name="removeMobileImage" id="removeCheckbox2" id=""
                           value="1">



                    <label for="title">Slayt Başlığı</label>
                    <input type="text" name="title" id="title" placeholder="Slayt Başlığı" required
                    value="{{$slider->title}}">

                    <label for="description">Slayt Açıklaması</label>
                    <input type="text" name="description" id="description" placeholder="Slayt Açıklaması"
                    value="{{$slider->description}}">

                    <label for="image">Slayt Resmi</label>
                    <input type="file" name="image" id="image" placeholder="Slayt Resmi">

                    <label for="mobileImage">Mobil Slayt Resmi</label>
                    <input type="file" name="mobile_image" id="mobileImage" placeholder="Mobil Slayt Resmi">

                    <label for="url">Yönlendirilecek URL</label>
                    <input type="text" name="url" id="url" placeholder="Yönlendirilecek URL"
                    value="{{$slider->url}}">

                    <label for="hit">Gösterim Sırası</label>
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası"
                    value="{{$slider->hit}}">

                    @if($languages->count()>1)
                    <label for="langId">Slayt Dili</label>
                    <select name="lang_id" id="langId">
                        @foreach($languages as $item)
                            <option value="{{$item->id}}" @if($item->id == $slider->lang_id) selected @endif>{{$item->name}}</option>
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

@section("extraJs")
    <script>
        function removeImage() {
            let categoryImage = document.getElementById('formFigure');
            const removeButton = document.getElementById("removeButton");
            const removeCheckbox=document.getElementById("removeCheckbox")
            if (categoryImage.style.display === "none"){
                categoryImage.style.display = "block";
                removeButton.classList.remove("bg-success");
                removeButton.classList.add("bg-error");
                removeButton.innerHTML="Resmi Sil";
                removeCheckbox.removeAttribute("checked");
            }else{
                categoryImage.style.display = "none";
                removeButton.classList.add("bg-success");
                removeButton.classList.remove("bg-error");
                removeButton.innerHTML="Resmi Geri Yükle";
                removeCheckbox.setAttribute("checked","checked");
            }
        }

        function removeMobileImage() {
            let categoryImage = document.getElementById('formFigure2');
            const removeButton = document.getElementById("removeButton2");
            const removeCheckbox = document.getElementById("removeCheckbox2")
            if (categoryImage.style.display === "none") {
                categoryImage.style.display = "block";
                removeButton.classList.remove("bg-success");
                removeButton.classList.add("bg-error");
                removeButton.innerHTML = "Mobil Resmi Sil";
                removeCheckbox.removeAttribute("checked");
            } else {
                categoryImage.style.display = "none";
                removeButton.classList.add("bg-success");
                removeButton.classList.remove("bg-error");
                removeButton.innerHTML = "Mobil Resmi Geri Yükle";
                removeCheckbox.setAttribute("checked", "checked");
            }
        }
    </script>
@endsection
