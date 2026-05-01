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
            <div class="card-header">Sıkça Sorulan Soru Ekle</div>
            <div class="card-body">
                <form action="{{ route('cms.faqs.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    {{-- Dinamik Sorular Alanı --}}
                    <div id="questions_wrapper">

                        {{-- 1. Varsayılan Soru (Sabit) --}}
                        <div class="question-row" style="margin-bottom: 50px; border-bottom: 1px dashed #ccc; padding-bottom: 20px;">
                            <h3 class="question-title">1. Soru</h3>

                            {{-- Soru --}}
                            <label>Soru</label>
                            <input type="text" name="question[]" placeholder="Soruyu Giriniz" required>

                            {{-- Cevap --}}
                            <label>Cevap</label>
                            <textarea name="answer[]" id="editor_0" placeholder="Cevabı Giriniz"  required></textarea>

                            {{-- Gösterim Sırası --}}
                            <label>Gösterim Sırası</label>
                            <input type="number" name="hit[]" value="1" placeholder="Gösterim Sırası" class="hit-input">
                        </div>

                    </div>

                    {{-- Yeni Soru Ekle Butonu --}}
                    <div style="text-align: right; margin-bottom: 20px;">
                        <input type="button" value="+ Yeni Soru Ekle" onclick="addQuestion()">
                    </div>

                    {{-- Bağlı Olduğu Sayfa --}}
                    @if($selectedPage)
                        <label for="page">Bağlı Olduğu Sayfa</label>
                        <select name="page_id" id="page" required>
                            <option value="{{ $selectedPage->id }}" selected>{{ $selectedPage->title }}</option>
                        </select>
                    @else
                        <p style="color: red;">❌ Sayfa bulunamadı veya geçersiz bağlantı.</p>
                    @endif

                    {{-- Diller --}}
                    @if($languages->count() > 1)
                        <label for="langId">Dil</label>
                        <select name="lang_id" id="langId">
                            @foreach($languages as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    <input type="submit" value="Kaydet">
                </form>

                {{-- Hata mesajları --}}
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection

@section("extraJs")
    <script src="{{asset("plugins/ckeditor/config.js")}}"></script>
    <script>
        // Sayfa yüklendiğinde ilk editörü aktif et
        if (typeof CKEDITOR !== "undefined") {
            CKEDITOR.replace('editor_0', {
                filebrowserWindowWidth: '1000',
                filebrowserWindowHeight: '700'
            });
        }

        // CKEditor ID çakışmasını önlemek için sürekli artan bir sayaç (Görünür sayıdan bağımsız)
        let uniqueCounter = 1;

        function addQuestion() {
            let wrapper = document.getElementById("questions_wrapper");

            // Editör için benzersiz ID (Asla çakışmaz)
            let newEditorId = "editor_dynamic_" + uniqueCounter;
            uniqueCounter++;

            let html = `
                <div class="question-row" style="margin-bottom: 50px; border-bottom: 1px dashed #ccc; padding-bottom: 20px;">

                    <h3 class="question-title" style="margin-bottom: 15px;">... Soru</h3>

                    <label>Soru</label>
                    <input type="text" name="question[]" placeholder="Soruyu Giriniz" required>

                    <label>Cevap</label>
                    <textarea name="answer[]" id="${newEditorId}" placeholder="Cevabı Giriniz" required></textarea>

                    <label>Gösterim Sırası</label>
                    <input type="number" name="hit[]" class="hit-input" placeholder="Gösterim Sırası">

                    <div style="margin-top: 15px; text-align: right;">
                        <input type="button" value="Sil" onclick="removeQuestion(this)" style="background-color: #dc3545; color: white; border:none; padding: 10px 20px; cursor: pointer;">
                    </div>
                </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);

            // Yeni textarea için CKEditor
            CKEDITOR.replace(newEditorId, {
                filebrowserWindowWidth: '1000',
                filebrowserWindowHeight: '700'
            });

            // Ekleme yapıldıktan sonra numaraları güncelle
            reorderQuestions();
        }

        // Silme Fonksiyonu
        function removeQuestion(buttonInfo) {
            // Tıklanan butonun içinde bulunduğu satırı (div) bul ve sil
            let row = buttonInfo.closest('.question-row');
            if(row) {
                row.remove();
            }
            // Silme işleminden sonra numaraları tekrar hesapla
            reorderQuestions();
        }

        // Sıralamayı ve Başlıkları Düzenleyen Fonksiyon
        function reorderQuestions() {
            let rows = document.querySelectorAll('.question-row');

            // Her bir satırı döngüye al
            rows.forEach((row, index) => {
                let number = index + 1; // 1'den başlasın

                // Başlığı güncelle (1. Soru, 2. Soru...)
                let title = row.querySelector('.question-title');
                if(title) {
                    title.innerText = number + ". Soru";
                }

                // Input değerini güncelle
                let input = row.querySelector('.hit-input'); // class="hit-input" eklemiştim
                // Sadece ilk eleman (varsayılan) için input class'ı olmayabilir, name ile bulalım
                if(!input) {
                    input = row.querySelector('input[name="hit[]"]');
                }

                if(input) {
                    input.value = number;
                }
            });
        }
    </script>
@endsection
