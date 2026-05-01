@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-8">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Toplu Referans Ekle</span>
                <a href="{{ route('cms.references.create') }}" class="btn btn-sm btn-secondary">
                    Tekli Ekle
                </a>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Bilgi:</strong> Aynı türe ait birden fazla logoyu tek seferde yükleyebilirsiniz.
                    Referans adları, dosya adlarından otomatik oluşturulur (örn: <code>toyota-logo.png</code> → <em>Toyota Logo</em>).
                    Yükleme sonrası tekli düzenleme ekranından isim, URL ve sıralamayı düzenleyebilirsiniz.
                </div>

                <form action="{{ route('cms.references.bulk-store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <label for="sezonId" class="form-label">Karnaval Sezonu:</label>
                    <select name="sezon_id" id="sezonId">
                        <option value="">Tüm Sezonlar</option>
                        @foreach($karnavalSezonlari as $item)
                            <option value="{{ $item->id }}">{{ $item->karnaval_yili }}</option>
                        @endforeach
                    </select>

                    <label for="type_id">Referans Türü <span style="color:red">*</span></label>
                    <select name="type_id" id="type_id" required>
                        <option value="">Seçiniz</option>
                        @foreach($refaceTypes as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    @if($languages->count() > 1)
                        <label for="langId">Dil</label>
                        <select name="lang_id" id="langId" required>
                            @foreach($languages as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="lang_id" value="{{ $languages->first()->id ?? 1 }}">
                    @endif

                    <label for="images">Referans Resimleri (birden fazla seçilebilir) <span style="color:red">*</span></label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" required>

                    <div id="preview-container" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:15px;"></div>

                    <input type="submit" value="Hepsini Kaydet" style="margin-top:20px;">
                </form>

                @if ($errors->any())
                    <ul style="color:red; margin-top:15px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('images').addEventListener('change', function (e) {
            const container = document.getElementById('preview-container');
            container.innerHTML = '';
            const files = Array.from(e.target.files);

            if (files.length === 0) return;

            const counter = document.createElement('div');
            counter.style.width = '100%';
            counter.style.fontWeight = 'bold';
            counter.textContent = files.length + ' dosya seçildi';
            container.appendChild(counter);

            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const wrapper = document.createElement('div');
                    wrapper.style.cssText = 'border:1px solid #ddd; padding:8px; border-radius:6px; width:140px; text-align:center; background:#fff;';

                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.style.cssText = 'width:100%; height:80px; object-fit:contain;';

                    const name = document.createElement('div');
                    name.textContent = file.name.replace(/\.[^/.]+$/, '').replace(/[-_]/g, ' ');
                    name.style.cssText = 'font-size:11px; margin-top:5px; word-break:break-word;';

                    wrapper.appendChild(img);
                    wrapper.appendChild(name);
                    container.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
