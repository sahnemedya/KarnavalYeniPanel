@extends("cms.partial.layout")

@section("extraCss")
    <link rel="stylesheet" href="{{asset('plugins/ckeditor/skins/moono/editor.css')}}">
    <script src="{{asset('plugins/ckeditor/lang/tr.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/styles.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('cms/page-videos.css') }}">
@endsection

@section("content")
    {{-- KAYNAK SAYFA (TÜRKÇE) VERİLERİNİ HESAPLIYORUZ --}}
    @php
        $isTranslation = !empty($page->translation_of);
        $sourcePage = $isTranslation ? \App\Models\Page::find($page->translation_of) : $page;

        // 1. DOSYAM
        $displayFile = $page->file ?? ($sourcePage->file ?? null);
        $fileUrl = $page->file ? $page->file() : ($sourcePage->file ? $sourcePage->file() : false);
        $fileExt = $displayFile ? pathinfo($displayFile, PATHINFO_EXTENSION) : '';
        $fileTitle = ($isTranslation && !$page->file && $sourcePage->file) ? 'Kaynak sayfanın dosyası. Değiştirmek isterseniz aşağıdan seçin.' : '';
        $hiddenFileValue = ($isTranslation && !$page->file && $sourcePage->file) ? $sourcePage->file : '';

        // 2. DOSYAM
        $displayLink = $page->link ?? ($sourcePage->link ?? null);
        $linkUrl = $page->link ? $page->link() : ($sourcePage->link ? $sourcePage->link() : false);
        $linkExt = $displayLink ? pathinfo($displayLink, PATHINFO_EXTENSION) : '';
        $linkTitle = ($isTranslation && !$page->link && $sourcePage->link) ? 'Kaynak sayfanın ikinci dosyası. Değiştirmek isterseniz aşağıdan seçin.' : '';
        $hiddenLinkValue = ($isTranslation && !$page->link && $sourcePage->link) ? $sourcePage->link : '';

        // SES KAYDI
        $displaySes = $page->ses ?? ($sourcePage->ses ?? null);
        $sesUrl = $page->ses ? $page->ses() : ($sourcePage->ses ? $sourcePage->ses() : false);
        $sesExt = $displaySes ? pathinfo($displaySes, PATHINFO_EXTENSION) : '';
        $sesTitle = ($isTranslation && !$page->ses && $sourcePage->ses) ? 'Kaynak sayfanın ses kaydı. Değiştirmek isterseniz aşağıdan seçin.' : '';
        $hiddenSesValue = ($isTranslation && !$page->ses && $sourcePage->ses) ? $sourcePage->ses : '';

        // METİN İNPUTLARI (Boşsa kaynak sayfanınkini otomatik doldurur)
        $valLink2 = $page->link2 ?? ($sourcePage->link2 ?? '');
        $valLink3 = $page->link3 ?? ($sourcePage->link3 ?? '');
        $valSpotify = $page->spotify ?? ($sourcePage->spotify ?? '');
        $valLocation = $page->location ?? ($sourcePage->location ?? '');
        $valVideo = $page->video ?? ($sourcePage->video ?? '');
        $valHeyzen = $page->heyzen ?? ($sourcePage->heyzen ?? '');
    @endphp

    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-12">
            <div class="card-header">{{$page->title}} Alt Sayfa Extra Alanları</div>
            <div class="card-body">
                <form id="extraForm" action="{{route('cms.side-menu-elements.extraStoreUpdate', $page->id)}}" method="post">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="pageSlug" value="{{ $page->slug }}">
                    {{-- Form kaydedildikten sonra geri dönülecek tam URL --}}
                    <input type="hidden" name="previous_url" value="{{ $previousUrl }}">

                    <div class="custom-media-grid mb-4">
                        {{-- 1. DOSYAM --}}
                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>1. Dosyam</h3>
                                @if($fileTitle) <p style="font-size:12px; color:#64748b; line-height:1.2; margin-bottom:5px;">{{ $fileTitle }}</p> @endif

                                <div class="media-preview">
                                    <a href="javascript:void(0)" onclick="openFileModal('{{ $fileUrl }}', '{{ $fileExt }}')" class="media-target text-decoration-none" style="display: {{ $displayFile ? 'flex' : 'none' }};">
                                        @if($fileExt == 'pdf')
                                            <img src="{{asset('images/panel/site/pdf-icon.png')}}" alt="PDF">
                                        @elseif(in_array($fileExt, ['doc', 'docx']))
                                            <img src="{{ asset('images/panel/site/word-icon.png') }}" alt="Word">
                                        @elseif(in_array($fileExt, ['xls', 'xlsx']))
                                            <img src="{{ asset('images/panel/site/excel-icon.png') }}" alt="Excel">
                                        @else
                                            <img src="{{ asset('images/panel/site/default-file.png') }}" alt="Dosya">
                                        @endif
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}" class="default-media-img" alt="Silindi" style="display: {{ $displayFile ? 'none' : 'block' }};">
                                </div>

                                <input type="file" class="async-file-input" data-type="file" id="file" placeholder="PDF vb. Seçin.">
                                <input type="hidden" name="uploaded_file" id="uploaded_file" value="{{ $hiddenFileValue }}">

                                <div class="upload-progress-wrapper" id="progress_wrapper_file" style="display: none; align-items: center; margin-top: 10px;">
                                    <div style="flex-grow: 1; height: 10px; background: #e2e8f0; border-radius: 5px; overflow: hidden;">
                                        <div id="progress_bar_file" style="width: 0%; height: 100%; background: #10b981; transition: width 0.2s;"></div>
                                    </div>
                                    <span id="progress_text_file" style="margin-left: 10px; font-weight: bold; font-size: 12px;">0%</span>
                                    <button type="button" class="btn bg-error" onclick="cancelUpload('file')" style="margin-left: 10px; padding: 2px 8px;" title="İptal Et">✖</button>
                                </div>

                                <button type="button" class="btn delete-image-btn bg-error mt-2" onclick="toggleMedia(this)" style="display: {{ $displayFile ? 'inline-block' : 'none' }};">Eski Dosyayı Sil</button>
                                <input type="checkbox" name="remove_file" class="d-none remove-checkbox">
                            </figure>
                        </div>

                        {{-- 2. DOSYAM --}}
                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>2. Dosyam</h3>
                                @if($linkTitle) <p style="font-size:12px; color:#64748b; line-height:1.2; margin-bottom:5px;">{{ $linkTitle }}</p> @endif

                                <div class="media-preview">
                                    <a href="javascript:void(0)" onclick="openFileModal('{{ $linkUrl }}', '{{ $linkExt }}')" class="media-target text-decoration-none" style="display: {{ $displayLink ? 'flex' : 'none' }};">
                                        @if($linkExt == 'pdf')
                                            <img src="{{asset('images/panel/site/pdf-icon.png')}}" alt="PDF">
                                        @elseif(in_array($linkExt, ['doc', 'docx']))
                                            <img src="{{ asset('images/panel/site/word-icon.png') }}" alt="Word">
                                        @elseif(in_array($linkExt, ['xls', 'xlsx']))
                                            <img src="{{ asset('images/panel/site/excel-icon.png') }}" alt="Excel">
                                        @else
                                            <img src="{{ asset('images/panel/site/default-file.png') }}" alt="Dosya">
                                        @endif
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}" class="default-media-img" alt="Silindi" style="display: {{ $displayLink ? 'none' : 'block' }};">
                                </div>

                                <input type="file" class="async-file-input" data-type="link" id="link" placeholder="PDF vb. Seçin.">
                                <input type="hidden" name="uploaded_link" id="uploaded_link" value="{{ $hiddenLinkValue }}">

                                <div class="upload-progress-wrapper" id="progress_wrapper_link" style="display: none; align-items: center; margin-top: 10px;">
                                    <div style="flex-grow: 1; height: 10px; background: #e2e8f0; border-radius: 5px; overflow: hidden;">
                                        <div id="progress_bar_link" style="width: 0%; height: 100%; background: #10b981; transition: width 0.2s;"></div>
                                    </div>
                                    <span id="progress_text_link" style="margin-left: 10px; font-weight: bold; font-size: 12px;">0%</span>
                                    <button type="button" class="btn bg-error" onclick="cancelUpload('link')" style="margin-left: 10px; padding: 2px 8px;" title="İptal Et">✖</button>
                                </div>

                                <button type="button" class="btn delete-image-btn bg-error mt-2" onclick="toggleMedia(this)" style="display: {{ $displayLink ? 'inline-block' : 'none' }};">Eski Dosyayı Sil</button>
                                <input type="checkbox" name="remove_link" class="d-none remove-checkbox">
                            </figure>
                        </div>

                        {{-- SES KAYDI --}}
                        <div class="media-grid-item">
                            <figure class="media-card">
                                <h3>Ses Kaydı</h3>
                                @if($sesTitle) <p style="font-size:12px; color:#64748b; line-height:1.2; margin-bottom:5px;">{{ $sesTitle }}</p> @endif

                                <div class="media-preview">
                                    <a href="javascript:void(0)" onclick="openFileModal('{{ $sesUrl }}', '{{ $sesExt }}')" class="media-target text-decoration-none" style="display: {{ $displaySes ? 'flex' : 'none' }};">
                                        <img src="{{ asset('images/panel/site/audio-icon.png') }}" alt="Ses Dosyası">
                                    </a>
                                    <img src="{{ asset('images/panel/site/default-placeholder.png') }}" class="default-media-img" alt="Silindi" style="display: {{ $displaySes ? 'none' : 'block' }};">
                                </div>

                                <input type="file" class="async-file-input" data-type="ses" id="ses" placeholder="Ses dosyası ekleyin.">
                                <input type="hidden" name="uploaded_ses" id="uploaded_ses" value="{{ $hiddenSesValue }}">

                                <div class="upload-progress-wrapper" id="progress_wrapper_ses" style="display: none; align-items: center; margin-top: 10px;">
                                    <div style="flex-grow: 1; height: 10px; background: #e2e8f0; border-radius: 5px; overflow: hidden;">
                                        <div id="progress_bar_ses" style="width: 0%; height: 100%; background: #10b981; transition: width 0.2s;"></div>
                                    </div>
                                    <span id="progress_text_ses" style="margin-left: 10px; font-weight: bold; font-size: 12px;">0%</span>
                                    <button type="button" class="btn bg-error" onclick="cancelUpload('ses')" style="margin-left: 10px; padding: 2px 8px;" title="İptal Et">✖</button>
                                </div>

                                <button type="button" class="btn delete-image-btn bg-error mt-2" onclick="toggleMedia(this)" style="display: {{ $displaySes ? 'inline-block' : 'none' }};">Eski Sesi Sil</button>
                                <input type="checkbox" name="remove_ses" class="d-none remove-checkbox">
                            </figure>
                        </div>
                    </div>

                    @include('components.videos-section')

                    <div class="row gx-0 card-ic" style="margin-top: 20px">
                        <div class="card-header">Linkler (yönlendirme)</div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="link2" class="form-label" style="display: block; margin-bottom: 8px;">Link</label>
                            <input type="text" name="link2" id="link2" placeholder="Yönlendirme Linki" value="{{ $valLink2 }}">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="link3" class="form-label" style="display: block; margin-bottom: 8px;">Link 2</label>
                            <input type="text" name="link3" id="link3" placeholder="Yönlendirme Linki" value="{{ $valLink3 }}">
                        </div>
                    </div>

                    <div class="row gx-0 card-ic" style="margin-top: 20px">
                        <div class="card-header">Spotify ve Harita</div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-left: 0 !important;">
                            <label for="spotify" class="form-label" style="display: block; margin-bottom: 8px;">Spotify (3 nokta->paylaş->bölümü göm)</label>
                            <input type="text" name="spotify" id="spotify" placeholder="Spotify" value="{{ $valSpotify }}">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="location" class="form-label" style="display: block; margin-bottom: 8px;">Location</label>
                            <input type="text" name="location" id="location" placeholder="Harita Yerleştirme Linki" value="{{ $valLocation }}">
                        </div>
                    </div>

                    <div class="row gx-0 card-ic" style="margin-top: 20px">
                        <div class="card-header">Diğerleri</div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 pe-2" style="padding-left: 0 !important;">
                            <label for="video" class="form-label" style="display: block; margin-bottom: 8px;">Video Linki (playlist)</label>
                            <input type="text" name="video" id="video" placeholder="Video" value="{{ $valVideo }}">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 mb-3 ps-2" style="padding-right: 0 !important;">
                            <label for="heyzen" class="form-label" style="display: block; margin-bottom: 8px;">Heyzine (src link)</label>
                            <input type="text" name="heyzen" id="heyzen" placeholder="Heyzine (src link)" value="{{ $valHeyzen }}">
                        </div>
                    </div>

                    <button type="submit" id="mainSubmitBtn" class="btn bg-primary mt-3">Kaydet</button>
                    <a href="{{ $previousUrl }}" class="btn bg-secondary mt-3 ms-2">Vazgeç ve Geri Dön</a>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="customFileModal" class="custom-file-modal">
        <div class="modal-content-wrapper">
            <button class="close-modal-btn" onclick="closeFileModal()">✖</button>
            <iframe id="fileIframe" src="" frameborder="0" style="display: none;"></iframe>
            <div id="sesContainer" class="ses-modal-container" style="display: none;">
                <audio id="modalSesPlayer" controls class="w-100"></audio>
            </div>
        </div>
    </div>
@endsection

@section("extraJs")
    <script>
        function toggleMedia(buttonElement) {
            const card = buttonElement.closest('.media-card');
            const mediaTarget = card.querySelector('.media-target');
            const defaultImg = card.querySelector('.default-media-img');
            const removeCheckbox = card.querySelector('.remove-checkbox');
            const hiddenInput = card.querySelector('input[type="hidden"][name^="uploaded_"]');

            if (mediaTarget.style.display === "none") {
                mediaTarget.style.display = "flex";
                if (defaultImg) defaultImg.style.display = "none";
                buttonElement.classList.replace("bg-success", "bg-error");
                buttonElement.innerHTML = "Eski Dosyayı Sil";
                removeCheckbox.checked = false;
            } else {
                mediaTarget.style.display = "none";
                if (defaultImg) defaultImg.style.display = "block";
                buttonElement.classList.replace("bg-error", "bg-success");
                buttonElement.innerHTML = "Geri Yükle";
                removeCheckbox.checked = true;
                if(hiddenInput) hiddenInput.value = '';
            }
        }

        function openFileModal(fileUrl, extension) {
            const modal = document.getElementById('customFileModal');
            const iframe = document.getElementById('fileIframe');
            const sesContainer = document.getElementById('sesContainer');
            const sesPlayer = document.getElementById('modalSesPlayer');
            const ext = extension ? extension.toLowerCase() : '';

            if (['mp3', 'wav', 'ogg'].includes(ext)) {
                iframe.style.display = 'none';
                iframe.src = '';
                sesContainer.style.display = 'flex';
                sesPlayer.src = fileUrl;
                sesPlayer.play();
            } else {
                sesContainer.style.display = 'none';
                sesPlayer.pause();
                sesPlayer.src = '';
                iframe.style.display = 'block';
                if (['doc', 'docx', 'xls', 'xlsx'].includes(ext)) {
                    const fullUrl = fileUrl.startsWith('http') ? fileUrl : window.location.origin + '/' + fileUrl.replace(/^\//, '');
                    iframe.src = `https://docs.google.com/gview?url=${encodeURIComponent(fullUrl)}&embedded=true`;
                } else {
                    iframe.src = fileUrl;
                }
            }
            modal.style.display = 'flex';
        }

        function closeFileModal() {
            const modal = document.getElementById('customFileModal');
            const iframe = document.getElementById('fileIframe');
            const sesContainer = document.getElementById('sesContainer');
            const sesPlayer = document.getElementById('modalSesPlayer');
            iframe.src = '';
            iframe.style.display = 'none';
            sesPlayer.pause();
            sesPlayer.src = '';
            sesContainer.style.display = 'none';
            modal.style.display = 'none';
        }

        // ASYNC YÜKLEME
        let tempFiles = { file: null, link: null, ses: null };
        let uploadControllers = { file: null, link: null, ses: null };
        let isFormSubmitting = false;
        let activeUploads = 0;

        const mainSubmitBtn = document.getElementById('mainSubmitBtn');

        document.querySelectorAll('.async-file-input').forEach(input => {
            input.addEventListener('change', function(e) {
                let file = e.target.files[0];
                if(!file) return;

                let type = this.getAttribute('data-type');
                let slug = document.getElementById('pageSlug').value;

                if(tempFiles[type]) {
                    deleteTempFile(type);
                }
                uploadAsync(file, type, slug);
            });
        });

        function uploadAsync(file, type, slug) {
            const progressWrapper = document.getElementById('progress_wrapper_' + type);
            const progressBar = document.getElementById('progress_bar_' + type);
            const progressText = document.getElementById('progress_text_' + type);
            const hiddenInput = document.getElementById('uploaded_' + type);

            progressWrapper.style.display = 'flex';
            progressBar.style.width = '0%';
            progressText.innerText = '0%';
            progressBar.style.background = '#10b981';

            activeUploads++;
            mainSubmitBtn.disabled = true;
            mainSubmitBtn.innerHTML = 'Dosyalar Yükleniyor...';

            uploadControllers[type] = new AbortController();

            let formData = new FormData();
            formData.append('file_data', file);
            formData.append('type', type);
            formData.append('slug', slug);

            axios.post('{{ route("cms.side-menu-elements.asyncUpload") }}', formData, {
                signal: uploadControllers[type].signal,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function(progressEvent) {
                    let percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressBar.style.width = percent + '%';
                    progressText.innerText = percent + '%';
                }
            }).then(response => {
                if(response.data.status === 'success') {
                    progressText.innerText = 'Yüklendi ✓';
                    tempFiles[type] = response.data.filename;
                    hiddenInput.value = response.data.filename;
                }
            }).catch(error => {
                if(axios.isCancel(error)) {
                    progressText.innerText = 'İptal Edildi';
                    progressBar.style.background = '#f59e0b';
                } else {
                    progressText.innerText = 'Hata!';
                    progressBar.style.background = '#ef4444';
                    notyf.error('Yükleme sırasında hata oluştu.');
                }
                hiddenInput.value = '';
                document.querySelector(`input[data-type="${type}"]`).value = '';
            }).finally(() => {
                activeUploads--;
                if(activeUploads <= 0) {
                    activeUploads = 0;
                    mainSubmitBtn.disabled = false;
                    mainSubmitBtn.innerHTML = 'Kaydet';
                }
            });
        }

        function cancelUpload(type) {
            if(uploadControllers[type]) {
                uploadControllers[type].abort();
            }
            if(tempFiles[type]) {
                deleteTempFile(type);
                document.getElementById('progress_text_' + type).innerText = 'Silindi';
                document.getElementById('progress_bar_' + type).style.width = '0%';
            }
            document.getElementById('uploaded_' + type).value = '';
            document.querySelector(`input[data-type="${type}"]`).value = '';

            setTimeout(() => {
                document.getElementById('progress_wrapper_' + type).style.display = 'none';
            }, 1000);
        }

        function deleteTempFile(type) {
            let payload = {};
            payload[type] = tempFiles[type];
            axios.post('{{ route("cms.side-menu-elements.asyncDelete") }}', { files: JSON.stringify(payload) }, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            tempFiles[type] = null;
        }

        document.getElementById('extraForm').addEventListener('submit', function(e) {
            isFormSubmitting = true;
            mainSubmitBtn.disabled = true;
            mainSubmitBtn.innerHTML = 'Sisteme İşleniyor...';
        });

        window.addEventListener('beforeunload', function (e) {
            let hasTempFiles = Object.values(tempFiles).some(val => val !== null);
            if (!isFormSubmitting && hasTempFiles) {
                e.preventDefault();
                e.returnValue = 'Yüklediğiniz dosyalar kaydedilmedi. Çıkmak istediğinize emin misiniz?';
            }
        });

        window.addEventListener('unload', function () {
            let hasTempFiles = Object.values(tempFiles).some(val => val !== null);
            if (!isFormSubmitting && hasTempFiles) {
                let data = new FormData();
                data.append('files', JSON.stringify(tempFiles));
                data.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                navigator.sendBeacon('{{ route("cms.side-menu-elements.asyncDelete") }}', data);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script src="{{ asset('cms/page-video-uploader.js') }}"></script>
    <script>
        window.PAGE_VIDEO_CONFIG = {
            pageId:    {{ $page->id }},
            pageSlug:  @json($page->slug),
            csrfToken: '{{ csrf_token() }}',
            videoStoragePath: @json(config('constants.video_path')),
            routes: {
                videoStore:     '{{ url("cms/side-menu-elements/page") }}/{pageId}/videos',
                videoUpdate:    '{{ url("cms/side-menu-elements/videos") }}/{videoId}',
                videoDestroy:   '{{ url("cms/side-menu-elements/videos") }}/{videoId}',
                videoReorder:   '{{ route("cms.side-menu-elements.videoReorder") }}',
                uploadInit:     '{{ route("cms.side-menu-elements.videoUploadInit") }}',
                uploadChunk:    '{{ url("cms/side-menu-elements/videos/upload") }}/{uploadId}/chunk',
                uploadFinalize: '{{ url("cms/side-menu-elements/videos/upload") }}/{uploadId}/finalize',
                uploadCancel:   '{{ url("cms/side-menu-elements/videos/upload") }}/{uploadId}',
                coverUpload:    '{{ route("cms.side-menu-elements.videoCoverUpload") }}',
                tempDelete:     '{{ route("cms.side-menu-elements.videoTempDelete") }}',
            }
        };
    </script>
    <script src="{{ asset('cms/page-videos.js') }}"></script>
@endsection
