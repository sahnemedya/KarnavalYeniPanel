{{--
    PAGE VIDEOS SECTION
    extra.blade.php içinde @include('cms.sideMenuElements._videos-section') olarak çağrılır.

    Gerekli değişkenler:
        $page    - Page modeli
        $videos  - PageVideo collection (controller'dan gelir)
--}}

<div class="row gx-0 card-ic page-videos-section" style="margin-top: 20px">
    <div class="card-header section-header">
        🎬 Sayfa Videoları
        <small class="float-end">
            ({{ $videos->count() }} video)
        </small>
    </div>

    <div class="section-body">

        {{-- Yeni Video Ekleme Butonu --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <small class="text-muted">
                Bu sayfada gösterilecek videoları yönetebilirsin.
                YouTube linki yapıştır ya da sunucuya direkt video yükle.
            </small>
            <button type="button" class="btn bg-success btn-sm" id="addNewVideoBtn">
                <i class="las la-plus"></i> Yeni Video Ekle
            </button>
        </div>

        {{-- VIDEO EKLEME / DÜZENLEME FORMU (gizli, butonla açılır) --}}
        <div class="video-form" id="videoForm" style="display: none;">
            <div class="video-form__title" id="videoFormTitle">Yeni Video Ekle</div>

            <input type="hidden" id="vf_id" value="">

            {{-- Başlık --}}
            <div class="video-form__row">
                <label>Video Başlığı *</label>
                <input type="text" id="vf_title" placeholder="Örn: Sürdürülebilirlik Tanıtım Videosu">
            </div>

            {{-- Kaynak Türü Sekmeleri --}}
            <div class="video-form__row">
                <label>Video Kaynağı *</label>
                <div class="source-tabs">
                    <button type="button" class="source-tab is-active" data-tab="youtube">
                        🔗 YouTube Linki
                    </button>
                    <button type="button" class="source-tab" data-tab="local">
                        📁 Sunucuya Yükle
                    </button>
                </div>

                <input type="hidden" id="vf_source_type" value="youtube">
                <input type="hidden" id="vf_source_value" value="">

                {{-- Panel: YouTube --}}
                <div class="source-panel is-active" data-panel="youtube">
                    <input type="text" id="vf_youtube_url"
                           placeholder="https://www.youtube.com/watch?v=... veya video ID">
                    <small class="text-muted d-block mt-1">
                        Tam URL yapıştırabilirsin, ID otomatik çıkarılır.
                    </small>
                </div>

                {{-- Panel: Local Upload --}}
                <div class="source-panel" data-panel="local">
                    <div class="video-dropzone" id="videoDropzone">
                        <div class="video-dropzone__icon">📤</div>
                        <div class="video-dropzone__text">Videoyu buraya sürükle veya tıkla</div>
                        <div class="video-dropzone__hint">MP4 / WebM / MOV / MKV — Max 1 GB</div>
                        <input type="file" id="videoFileInput"
                               accept="video/mp4,video/webm,video/quicktime,video/x-matroska">
                    </div>

                    <div class="video-upload-status" id="videoUploadStatus" style="display: none;">
                        <div class="video-upload-status__filename" id="videoUploadFilename"></div>
                        <div class="video-upload-status__bar">
                            <div class="video-upload-status__fill" id="videoUploadFill"></div>
                        </div>
                        <div class="video-upload-status__info">
                            <span id="videoUploadStatusText">Hazırlanıyor...</span>
                            <span>
                                <span id="videoUploadPercent">0%</span>
                                <button type="button" class="btn bg-error btn-sm ms-2"
                                        id="videoUploadCancelBtn"
                                        style="padding: 2px 8px;">İptal</button>
                            </span>
                        </div>
                    </div>

                    <div class="video-uploaded-preview" id="videoUploadedPreview" style="display: none;">
                        <span><i class="las la-check-circle"></i> <strong id="videoUploadedName"></strong> yüklendi</span>
                        <button type="button" class="btn bg-error btn-sm" id="videoUploadedRemove"
                                style="padding: 2px 10px;">Kaldır</button>
                    </div>
                </div>
            </div>

            {{-- Kapak Görseli --}}
            <div class="video-form__row">
                <label>Kapak Görseli</label>
                <input type="hidden" id="vf_cover_image" value="">
                <input type="file" id="vf_cover_input" accept="image/*">
                <div id="vf_cover_progress" style="display: none; margin-top: 8px;">
                    <small class="text-muted" id="vf_cover_status">Yükleniyor...</small>
                </div>
                <div id="vf_cover_preview" style="display: none; margin-top: 8px;">
                    <img id="vf_cover_img" src="" alt="" style="width: 120px; height: 70px; object-fit: cover; border-radius: 4px;">
                    <button type="button" class="btn bg-error btn-sm ms-2" id="vf_cover_remove"
                            style="padding: 2px 10px;">Kaldır</button>
                </div>
            </div>

            {{-- Aktif --}}
            <div class="video-form__row">
                <label>
                    <input type="checkbox" id="vf_is_active" checked>
                    Yayında
                </label>
            </div>

            {{-- Butonlar --}}
            <div class="d-flex gap-2">
                <button type="button" class="btn bg-primary" id="videoFormSaveBtn">Kaydet</button>
                <button type="button" class="btn bg-secondary" id="videoFormCancelBtn">İptal</button>
            </div>
        </div>

        {{-- VIDEO LİSTESİ --}}
        <ul class="video-list" id="videoList">
            @forelse($videos as $video)
                <li class="video-item" data-id="{{ $video->id }}">
                    <span class="video-item__handle" title="Sürükle">⋮⋮</span>

                    @if($video->coverImage())
                        <img src="{{ $video->coverImage() }}" class="video-item__cover" alt="">
                    @else
                        <div class="video-item__cover video-item__cover--placeholder">Kapak Yok</div>
                    @endif

                    <div class="video-item__info">
                        <div class="video-item__title">{{ $video->title }}</div>
                        <div class="video-item__meta">
                            @if($video->source_type === 'youtube')
                                <span class="video-item__badge video-item__badge--youtube">YouTube</span>
                                <small>ID: {{ $video->source_value }}</small>
                            @else
                                <span class="video-item__badge video-item__badge--local">Yüklü Dosya</span>
                                <small>{{ $video->source_value }}</small>
                            @endif

                            @if(! $video->is_active)
                                <span class="video-item__badge video-item__badge--inactive">Pasif</span>
                            @endif
                        </div>
                    </div>

                    <div class="video-item__actions">
                        <button type="button" class="video-item__btn video-item__btn--edit"
                                data-action="edit"
                                data-video='@json($video)'>
                            Düzenle
                        </button>
                        <button type="button" class="video-item__btn video-item__btn--delete"
                                data-action="delete"
                                data-id="{{ $video->id }}">
                            Sil
                        </button>
                    </div>
                </li>
            @empty
                <li class="video-empty" id="videoEmptyMessage">
                    Henüz video eklenmemiş. "Yeni Video Ekle" butonuna tıklayarak başla.
                </li>
            @endforelse
        </ul>

    </div>
</div>
