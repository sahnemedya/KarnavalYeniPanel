@extends("cms.partial.layout")
@section("content")
    <div class="card">
        <div class="card-header"><span class="text-warning">{{$category->name}}</span> Sayfa Listesi</div>
        <div class="card-body">
            <table id="datatable" class="display stripe table-responsive-sm table-responsive-md"
                   style="width:100%">

                <thead>
                <tr>
                    <th>Karnaval Yılı</th>
                    <th>Sayfa Adı</th>
                    @if(request('lang_id') && request('lang_id') != 1)
                        <th>Türkçesi</th>
                    @endif
                    <th>Resim</th>
                    <th>Bağlı Sayfa</th>
                    @if(!request('lang_id') || request('lang_id') == 1)
                        <th>Dil</th>
                    @endif


                    <th title="Yayına Al">YA</th>
                    <th title="Navbarda Göster">NG</th>
                    <th title="Ana Sayfada Göster">AG</th>
                    <th title="Footerda Göster">FG</th>
                    <th>Extra Dosyalar</th>
                    <th>Özellikler</th>

                    <th>İşlem</th>
                </tr>
                </thead>

                <tbody>
                @foreach($pages as $item)

                    <tr data-id="{{ $item->id }}">
                        <th>{{ $item->karnavalSezonus?->karnaval_yili ?? 'Tüm Sezonlar' }}</th>
                        <th>{{$item->title}}</th>
                        @if(request('lang_id') && request('lang_id') != 1)
                            {{--
                                $item->translation_of -> Sadece ID (Sayı) döner.
                                $item->originalPage   -> O ID'ye ait Model nesnesini döner.
                            --}}

                            <th>
                                {{-- Eğer bağlı sayfa varsa başlığını yaz, yoksa boş bırak --}}
                                {{ $item->originalPage?->title }}
                            </th>

                        @endif
                        <th>
                            @if($item->image())
                                <figure data-fancybox="Page List" data-src="{{$item->image()}}"
                                        data-caption="{{$item->title}}">
                                    <img src="{{$item->image()}}" width="35" height="35" alt="">
                                </figure>
                            @else
                                Resim Yok
                            @endif
                        </th>

                        <th>
                            @if($item->parent_page)

                                {{ $item->parent->title}}
                            @else
                                Üst Sayfası Yok
                            @endif
                        </th>
                        {{-- Resimden sonraki td/th sırasına denk gelecek şekilde --}}
                        {{-- ... Diğer td etiketleri ... --}}

                        {{-- Sadece Türkçe (Varsayılan) moddaysak Dil butonlarını göster --}}
                        @if(!request('lang_id') || request('lang_id') == 1)
                            <th>
                                <div class="d-flex gap-1">
                                    @foreach($languages as $index => $lang)
                                        @if($index == 0) @continue @endif

                                        @php
                                            $translation = $item->translations->where('lang_id', $lang->id)->first();
                                        @endphp

                                        @if($translation)
                                            <a href="{{ route('cms.side-menu-elements.edit', [$translation->category_id, $translation->id]) }}"
                                               class="btn btn-sm btn-info"
                                               title="{{ $lang->name }} Düzenle">
                                                {{ $lang->code }}
                                            </a>
                                        @else
                                            <a href="{{ route('cms.side-menu-elements.createLanguage', [$category->id, $item->id]) }}"
                                               class="btn btn-sm btn-secondary"
                                               title="{{ $lang->name }} Ekle">
                                                <i class="las la-plus"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </th>
                        @endif

                        {{-- ... İşlem butonları ... --}}
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       onclick="activate('{{route("cms.pages.activate",$item->id)}}','published')"
                                       @if($item->published) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>

                        {{-- Menüde Göster --}}
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       onclick="activate('{{route("cms.side-menu-elements.showMenu",$item->id)}}','show_menu')"
                                       @if($item->show_menu) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>

                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       onclick="activate('{{route("cms.side-menu-elements.showHomePage",$item->id)}}','show_homepage')"
                                       @if($item->show_homepage) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th>
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                       onclick="activate('{{route("cms.pages.activate",$item->id)}}','show_footer')"
                                       @if($item->show_footer) checked @endif>
                                <span class="switch-slider"></span>
                            </label>
                        </th>
                        <th >
                            <a href="{{ route('cms.side-menu-elements.extraedit', $item->id) }}" class="btn bg-primary" title="Extra Alan">
                                <i class="las la-folder-open"></i>
                            </a>

                        </th>
                        {{-- Bu kısmı sideMenuElements/index.blade.php içindeki Özellikler th/td kısmıyla değiştir --}}
                        <th>
                            <a href="{{ route('cms.features.create', ['page_id' => $item->id]) }}"
                               class="btn bg-light" title="{{ $item->title }} Sayfanın Özelliğini Ekle">
                                <i class="las la-clipboard-list"></i>
                            </a>

                            @php
                                $featureCount = \App\Models\Feature::where('page_id', $item->id)->count();
                            @endphp

                            @if($featureCount > 0)
                                {{-- SADECE BU SAYFAYA AİT ÖZELLİKLERİ LİSTELEMEK İÇİN PAGE_ID GÖNDERİYORUZ --}}
                                <a href="{{ route('cms.features.index', ['page_id' => $item->id]) }}"
                                   class="btn bg-primary mt-1" title="Bu Sayfanın Özelliklerini Düzenle">
                                    <i class="las la-pen"></i>
                                </a>
                            @endif
                        </th>

                        <th class="islemler">
                            <a href="{{route("cms.side-menu-elements.edit",[$category->id,$item->id])}}"
                               class="btn bg-primary" title="düzenle">
                                <i class="las la-pen"></i>
                            </a>
                            <a href="{{ route('cms.faqs.create', ['page_id' => $item->id]) }}"
                               class="btn bg-warning" title="{{ $item->title }} Sayfasına SSS Ekle">
                                <i class="las la-question-circle"></i>
                            </a>



                            <a href="{{route("cms.gallery.add-images",[$item->id])}}"
                               class="btn bg-success" title="{{$item->title}} Sayfasına Galeri Resimleri Ekle">
                                <i class="las la-images"></i>
                            </a>

                            <a onclick="deleteFunc('{{route("cms.pages.destroy",$item->id)}}')"
                               class="btn bg-error" title="Sil">
                                <i class="las la-trash"></i>
                            </a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section("extraJs")
    <script>
        $(document).ready(function() {
            // Eğer tablo layout dosyasında önceden başlatılmışsa hata vermemesi için destroy: true ekliyoruz.
            $('#datatable').DataTable({
                destroy: true, // Önceki DataTables kopyasını yok edip yeniden kurar
                pageLength: -1, // Varsayılan olarak seçili gelecek sayı. -1 "Tümü" anlamına gelir.
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tümü"]], // Dropdown'da görünecek seçenekler
                language: {
                    // Türkçe dil desteği eksikse buraya ekleyebilirsin
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json'
                }
            });
        });
    </script>
    <script>
        function deleteFunc(route) {
            axios.delete(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                        setInterval(function () {
                            window.location.reload();
                        }, 1500)
                    } else if (response.data.status === "warning") {
                        notyf.open({
                            type: "warning",
                            message: response.data.message
                        });
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error(response.data.message);
                });
        }

        function publishPage(route) {
            axios.post(route, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                    } else if (response.data.status === "warning") {
                        notyf.open({
                            type: "warning",
                            message: response.data.message
                        });
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error("Bir hata oluştu.");
                });
        }

        function activate(route, type) {
            axios.post(route, {
                type: type
            }, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (response.data.status === "success") {
                        notyf.success(response.data.message);
                    } else if (response.data.status === "warning") {
                        notyf.open({
                            type: "warning",
                            message: response.data.message
                        });
                    } else {
                        notyf.error(response.data.message);
                    }
                })
                .catch(error => {
                    notyf.error("Bir hata oluştu.");
                });
        }
    </script>
@endsection
