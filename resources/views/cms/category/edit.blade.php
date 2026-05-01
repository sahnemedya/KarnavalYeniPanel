@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Kategori Güncelle</div>
            <div class="card-body">
                <form action="{{route("cms.category.update",$category->id)}}" method="post"
                      enctype="multipart/form-data">
                    @method("PUT")
                    @csrf

                    @if($category->image)
                        <figure class="form-figure">
                            <img src="{{$category->Image()}}" id="formFigure" alt="">
                            <label onclick="removeImage()" class="btn delete-image-btn bg-error" id="removeButton">Resmi
                                Sil</label>
                        </figure>
                    @endif
                    <input type="checkbox" style="display: none" name="removeImage" id="removeCheckbox" id="" value="1">
                    <input type="text" name="name" id="name" placeholder="Kategori Adı" value="{{$category->name}}"
                           required>
                    <input type="text" name="note" id="note" placeholder="Kısa Not" value="{{$category->note}}">
                    <input type="file" name="category_image" id="category_image" placeholder="Kategori Resmi">
                    <input type="number" name="hit" id="hit" placeholder="Gösterim Sırası"
                           value="{{$category->hit}}">
                    <select name="parent_category" id="parent_category">
                        <option selected disabled hidden>Üst Kategori</option>
                        <option value="" @if($category->parent_category==NULL) selected @endif>Ana Menü</option>
                        @foreach($categories as $item)
                            <option value="{{$item->id}}" @if($category->parent_category == $item->id) selected
                                    class="text-success" @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                    {{--                    Eğer anadilde ise translation of gizli olacak--}}
                    <select name="translation_of" id="translation_of"
                            title="Eğer eklediğiniz kategori çeviri sayfası ise, yani site ana dilinden farklı bir dil için ekleniyorsa hangi sayfanın çeviri olduğunu seçin.">
                        <option value="" @if($category->parent_category==NULL) selected @endif>Çeviri Değil (?)</option>
                        @foreach($categories as $item)
                            <option value="{{$item->id}}" @if($category->translation_of == $item->id) selected
                                    class="text-success" @endif>{{$item->name}}</option>
                        @endforeach
                    </select>

                    @if($languages->count()>1)
                    <select name="lang_id" id="lang_id">
                        @foreach($languages as $item)
                            <option value="{{$item->id}}" @if($category->lang_id == $item->id) selected
                                    class="text-success" @endif>{{$item->name}}</option>
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
    </script>
@endsection

