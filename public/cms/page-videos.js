/**
 * page-videos.js
 * extra.blade.php'in sonunda yüklenir.
 * Sayfa video yönetiminin tüm UI logic'i burada.
 *
 * Bağımlılıklar:
 *   - axios (mevcut)
 *   - notyf (mevcut)
 *   - SortableJS (yeni - CDN'den)
 *   - ChunkedVideoUploader (page-video-uploader.js)
 *
 * Window'da bu objeler beklenir:
 *   window.PAGE_VIDEO_CONFIG = {
 *       pageId, pageSlug, csrfToken, routes: {...}
 *   }
 */
(function () {
    'use strict';

    if (! window.PAGE_VIDEO_CONFIG) {
        console.warn('PAGE_VIDEO_CONFIG bulunamadı. Page videos başlatılamadı.');
        return;
    }

    const cfg = window.PAGE_VIDEO_CONFIG;

    // ========== ELEMENT REFERANSLARI ==========
    const $ = (id) => document.getElementById(id);

    const addBtn         = $('addNewVideoBtn');
    const formBox        = $('videoForm');
    const formTitle      = $('videoFormTitle');
    const inputId        = $('vf_id');
    const inputTitle     = $('vf_title');
    const inputSrcType   = $('vf_source_type');
    const inputSrcValue  = $('vf_source_value');
    const youtubeUrlEl   = $('vf_youtube_url');
    const inputCover     = $('vf_cover_image');
    const coverInput     = $('vf_cover_input');
    const coverPreview   = $('vf_cover_preview');
    const coverImg       = $('vf_cover_img');
    const coverRemoveBtn = $('vf_cover_remove');
    const coverProgress  = $('vf_cover_progress');
    const coverStatus    = $('vf_cover_status');
    const inputActive    = $('vf_is_active');
    const saveBtn        = $('videoFormSaveBtn');
    const cancelBtn      = $('videoFormCancelBtn');

    const dropzone        = $('videoDropzone');
    const fileInput       = $('videoFileInput');
    const uploadStatus    = $('videoUploadStatus');
    const uploadFilename  = $('videoUploadFilename');
    const uploadFill      = $('videoUploadFill');
    const uploadStatusTxt = $('videoUploadStatusText');
    const uploadPercent   = $('videoUploadPercent');
    const uploadCancelBtn = $('videoUploadCancelBtn');
    const uploadedPreview = $('videoUploadedPreview');
    const uploadedName    = $('videoUploadedName');
    const uploadedRemove  = $('videoUploadedRemove');

    const videoList = $('videoList');

    let activeUploader = null;
    let editingVideoId = null;

    // ========== SEKME GEÇİŞİ ==========
    document.querySelectorAll('.source-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;
            document.querySelectorAll('.source-tab').forEach(t => t.classList.remove('is-active'));
            document.querySelectorAll('.source-panel').forEach(p => p.classList.remove('is-active'));
            tab.classList.add('is-active');
            document.querySelector(`.source-panel[data-panel="${target}"]`).classList.add('is-active');

            inputSrcType.value = target;

            // Sekme değişince source_value senkronize et
            if (target === 'youtube') {
                inputSrcValue.value = youtubeUrlEl.value.trim();
            } else {
                // Yüklü dosya varsa onu koru, yoksa boşalt
                if (uploadedPreview.style.display !== 'flex') {
                    inputSrcValue.value = '';
                }
            }
        });
    });

    youtubeUrlEl.addEventListener('input', (e) => {
        if (inputSrcType.value === 'youtube') {
            inputSrcValue.value = e.target.value.trim();
        }
    });

    // ========== FORMU AÇ / KAPA ==========
    addBtn.addEventListener('click', () => {
        editingVideoId = null;
        resetForm();
        formTitle.textContent = 'Yeni Video Ekle';
        formBox.style.display = 'block';
        formBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    cancelBtn.addEventListener('click', () => {
        if (uploadedPreview.style.display === 'flex' && inputSrcValue.value) {
            // Yüklenmiş ama kaydedilmemiş video varsa sunucudan sil
            deleteTempVideo(inputSrcValue.value);
        }
        if (coverPreview.style.display === 'block' && inputCover.value && ! editingVideoId) {
            deleteTempCover(inputCover.value);
        }
        resetForm();
        formBox.style.display = 'none';
    });

    function resetForm() {
        inputId.value = '';
        inputTitle.value = '';
        inputSrcType.value = 'youtube';
        inputSrcValue.value = '';
        youtubeUrlEl.value = '';
        inputCover.value = '';
        inputActive.checked = true;
        coverPreview.style.display = 'none';
        coverProgress.style.display = 'none';
        coverInput.value = '';
        coverImg.src = '';
        uploadStatus.style.display = 'none';
        uploadedPreview.style.display = 'none';
        dropzone.style.display = 'block';
        fileInput.value = '';
        // Sekmeyi YouTube'a al
        document.querySelector('.source-tab[data-tab="youtube"]').click();
    }

    // ========== KAPAK GÖRSELİ YÜKLEME ==========
    coverInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (! file) return;

        coverProgress.style.display = 'block';
        coverStatus.textContent = 'Yükleniyor...';

        const fd = new FormData();
        fd.append('file_data', file);
        fd.append('slug', cfg.pageSlug);

        try {
            const resp = await axios.post(cfg.routes.coverUpload, fd, {
                headers: { 'X-CSRF-TOKEN': cfg.csrfToken }
            });

            if (resp.data.status === 'success') {
                // Eğer önceden yüklenmiş ama henüz kaydedilmemiş bir kapak varsa onu sil
                if (inputCover.value && inputCover.value !== resp.data.filename && ! editingVideoId) {
                    deleteTempCover(inputCover.value);
                }
                inputCover.value = resp.data.filename;
                coverImg.src = resp.data.url;
                coverPreview.style.display = 'block';
                coverProgress.style.display = 'none';
            } else {
                coverStatus.textContent = 'Hata: ' + resp.data.message;
                notyf.error('Kapak yüklenemedi.');
            }
        } catch (err) {
            coverStatus.textContent = 'Hata!';
            notyf.error('Kapak yüklenemedi.');
        }
    });

    coverRemoveBtn.addEventListener('click', () => {
        if (inputCover.value && ! editingVideoId) {
            deleteTempCover(inputCover.value);
        }
        inputCover.value = '';
        coverImg.src = '';
        coverPreview.style.display = 'none';
        coverInput.value = '';
    });

    function deleteTempCover(filename) {
        axios.post(cfg.routes.tempDelete, { filename }, {
            headers: { 'X-CSRF-TOKEN': cfg.csrfToken }
        }).catch(() => { /* sessizce yut */ });
    }

    // ========== VIDEO DOSYA YÜKLEME (CHUNKED) ==========
    dropzone.addEventListener('click', () => fileInput.click());

    ['dragenter', 'dragover'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.add('is-dragover');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropzone.addEventListener(evt, (e) => {
            e.preventDefault();
            dropzone.classList.remove('is-dragover');
        });
    });
    dropzone.addEventListener('drop', (e) => {
        if (e.dataTransfer.files.length > 0) handleVideoFile(e.dataTransfer.files[0]);
    });
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) handleVideoFile(e.target.files[0]);
    });

    function handleVideoFile(file) {
        const allowed = ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-matroska'];
        if (file.type && ! allowed.includes(file.type)) {
            notyf.error('Geçersiz dosya türü. MP4, WebM, MOV veya MKV yükleyin.');
            return;
        }
        if (file.size > 1024 * 1024 * 1024) {
            notyf.error('Dosya çok büyük. Maksimum 1 GB.');
            return;
        }

        // Eski yüklü video varsa sil
        if (uploadedPreview.style.display === 'flex' && inputSrcValue.value) {
            deleteTempVideo(inputSrcValue.value);
            inputSrcValue.value = '';
        }

        dropzone.style.display = 'none';
        uploadedPreview.style.display = 'none';
        uploadStatus.style.display = 'block';
        uploadFilename.textContent = file.name;
        uploadFill.style.width = '0%';
        uploadFill.classList.remove('is-error', 'is-complete');
        uploadPercent.textContent = '0%';
        saveBtn.disabled = true;

        activeUploader = new ChunkedVideoUploader({
            file: file,
            pageSlug: cfg.pageSlug,
            csrfToken: cfg.csrfToken,
            endpoints: {
                init:     cfg.routes.uploadInit,
                chunk:    cfg.routes.uploadChunk,    // {uploadId} placeholder ile
                finalize: cfg.routes.uploadFinalize, // {uploadId} placeholder ile
                cancel:   cfg.routes.uploadCancel,   // {uploadId} placeholder ile
            },
            onProgress: (percent) => {
                uploadFill.style.width = percent + '%';
                uploadPercent.textContent = percent + '%';
            },
            onStatusChange: (status, msg) => {
                uploadStatusTxt.textContent = msg;
            },
            onComplete: (filename, url) => {
                uploadFill.classList.add('is-complete');
                uploadFill.style.width = '100%';
                uploadPercent.textContent = '100%';

                inputSrcType.value = 'local';
                inputSrcValue.value = filename;

                setTimeout(() => {
                    uploadStatus.style.display = 'none';
                    uploadedPreview.style.display = 'flex';
                    uploadedName.textContent = filename;
                    saveBtn.disabled = false;
                }, 600);
            },
            onError: (msg) => {
                uploadFill.classList.add('is-error');
                saveBtn.disabled = false;
                notyf.error('Video yüklenemedi: ' + msg);
                setTimeout(() => {
                    uploadStatus.style.display = 'none';
                    dropzone.style.display = 'block';
                    fileInput.value = '';
                }, 2000);
            }
        });

        activeUploader.start();
    }

    uploadCancelBtn.addEventListener('click', () => {
        if (activeUploader && confirm('Yükleme iptal edilsin mi?')) {
            activeUploader.cancel();
            saveBtn.disabled = false;
            setTimeout(() => {
                uploadStatus.style.display = 'none';
                dropzone.style.display = 'block';
                fileInput.value = '';
            }, 1000);
        }
    });

    uploadedRemove.addEventListener('click', () => {
        if (! confirm('Yüklenmiş videoyu kaldırmak istediğine emin misin?')) return;
        if (inputSrcValue.value) deleteTempVideo(inputSrcValue.value);
        inputSrcValue.value = '';
        uploadedPreview.style.display = 'none';
        dropzone.style.display = 'block';
        fileInput.value = '';
    });

    function deleteTempVideo(filename) {
        axios.post(cfg.routes.tempDelete, { filename }, {
            headers: { 'X-CSRF-TOKEN': cfg.csrfToken }
        }).catch(() => { /* sessizce yut */ });
    }

    // ========== KAYDET ==========
    saveBtn.addEventListener('click', async () => {
        // Validation
        if (! inputTitle.value.trim()) {
            notyf.error('Başlık boş olamaz.');
            return;
        }
        if (! inputSrcValue.value.trim()) {
            notyf.error('YouTube linki gir veya video yükle.');
            return;
        }

        const payload = {
            title:        inputTitle.value.trim(),
            source_type:  inputSrcType.value,
            source_value: inputSrcValue.value.trim(),
            cover_image:  inputCover.value || null,
            is_active:    inputActive.checked ? 1 : 0,
        };

        saveBtn.disabled = true;
        saveBtn.textContent = 'Kaydediliyor...';

        try {
            let resp;
            if (editingVideoId) {
                resp = await axios.put(
                    cfg.routes.videoUpdate.replace('{videoId}', editingVideoId),
                    payload,
                    { headers: { 'X-CSRF-TOKEN': cfg.csrfToken } }
                );
            } else {
                resp = await axios.post(
                    cfg.routes.videoStore.replace('{pageId}', cfg.pageId),
                    payload,
                    { headers: { 'X-CSRF-TOKEN': cfg.csrfToken } }
                );
            }

            if (resp.data.status === 'success') {
                notyf.success(resp.data.message);
                // Sayfayı yenileyelim ki liste güncel gelsin (basit ve güvenli)
                setTimeout(() => window.location.reload(), 600);
            } else {
                notyf.error(resp.data.message || 'Kayıt başarısız.');
                saveBtn.disabled = false;
                saveBtn.textContent = 'Kaydet';
            }
        } catch (err) {
            notyf.error('Sunucu hatası: ' + (err.response?.data?.message || err.message));
            saveBtn.disabled = false;
            saveBtn.textContent = 'Kaydet';
        }
    });

    // ========== DÜZENLE / SİL (Liste butonları) ==========
    videoList.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action]');
        if (! btn) return;

        if (btn.dataset.action === 'edit') {
            const video = JSON.parse(btn.dataset.video);
            openEditForm(video);
        } else if (btn.dataset.action === 'delete') {
            if (! confirm('Bu video silinsin mi?')) return;

            try {
                const resp = await axios.delete(
                    cfg.routes.videoDestroy.replace('{videoId}', btn.dataset.id),
                    { headers: { 'X-CSRF-TOKEN': cfg.csrfToken } }
                );
                if (resp.data.status === 'success') {
                    notyf.success(resp.data.message);
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    notyf.error(resp.data.message || 'Silme başarısız.');
                }
            } catch (err) {
                notyf.error('Sunucu hatası.');
            }
        }
    });

    function openEditForm(video) {
        editingVideoId = video.id;
        resetForm();

        formTitle.textContent = 'Videoyu Düzenle: ' + video.title;
        inputId.value = video.id;
        inputTitle.value = video.title;
        inputActive.checked = !! video.is_active;
        inputSrcType.value = video.source_type;
        inputSrcValue.value = video.source_value;

        // İlgili sekmeyi aç
        document.querySelectorAll('.source-tab').forEach(t => t.classList.remove('is-active'));
        document.querySelectorAll('.source-panel').forEach(p => p.classList.remove('is-active'));
        document.querySelector(`.source-tab[data-tab="${video.source_type}"]`).classList.add('is-active');
        document.querySelector(`.source-panel[data-panel="${video.source_type}"]`).classList.add('is-active');

        if (video.source_type === 'youtube') {
            youtubeUrlEl.value = video.source_value;
        } else {
            uploadedPreview.style.display = 'flex';
            uploadedName.textContent = video.source_value;
            dropzone.style.display = 'none';
        }

        if (video.cover_image) {
            inputCover.value = video.cover_image;
            coverImg.src = '/storage/' + (cfg.videoStoragePath || 'images/user/videos') + '/' + video.cover_image;
            coverPreview.style.display = 'block';
        }

        formBox.style.display = 'block';
        formBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // ========== SÜRÜKLE-BIRAK SIRALAMA ==========
    if (window.Sortable && videoList) {
        new Sortable(videoList, {
            handle: '.video-item__handle',
            animation: 150,
            ghostClass: 'is-dragging',
            onEnd: async () => {
                const orderedIds = Array.from(videoList.querySelectorAll('.video-item'))
                    .map(li => parseInt(li.dataset.id, 10));

                try {
                    await axios.post(cfg.routes.videoReorder, { ordered_ids: orderedIds }, {
                        headers: { 'X-CSRF-TOKEN': cfg.csrfToken }
                    });
                    notyf.success('Sıralama kaydedildi.');
                } catch (err) {
                    notyf.error('Sıralama kaydedilemedi.');
                }
            }
        });
    }
})();
