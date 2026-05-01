/**
 * ChunkedVideoUploader
 * Büyük video dosyalarını parçalara böler ve sırayla yükler.
 * Mevcut asyncUpload pattern'inin chunked versiyonu.
 *
 * axios bağımlılığı vardır (zaten projede mevcut).
 */
class ChunkedVideoUploader {
    constructor(options) {
        this.file        = options.file;
        this.pageSlug    = options.pageSlug;
        this.endpoints   = options.endpoints; // { init, chunk, finalize, cancel }
        this.csrfToken   = options.csrfToken;

        this.onProgress     = options.onProgress     || (() => {});
        this.onStatusChange = options.onStatusChange || (() => {});
        this.onComplete     = options.onComplete     || (() => {});
        this.onError        = options.onError        || (() => {});

        this.chunkSize       = 5 * 1024 * 1024;
        this.uploadId        = null;
        this.totalChunks     = 0;
        this.uploadedChunks  = 0;
        this.cancelled       = false;
        this.cancelTokenSrc  = null;

        this.maxRetries      = 3;
        this.retryDelay      = 2000;
    }

    async start() {
        try {
            this.onStatusChange('initializing', 'Yükleme başlatılıyor...');

            // 1. INIT
            const initResp = await axios.post(this.endpoints.init, {
                filename:   this.file.name,
                total_size: this.file.size,
                mime_type:  this.file.type || 'application/octet-stream',
            }, {
                headers: { 'X-CSRF-TOKEN': this.csrfToken }
            });

            if (initResp.data.status !== 'success') {
                throw new Error(initResp.data.message || 'Init başarısız.');
            }

            this.uploadId    = initResp.data.upload_id;
            this.chunkSize   = initResp.data.chunk_size || this.chunkSize;
            this.totalChunks = Math.ceil(this.file.size / this.chunkSize);

            this.onStatusChange('uploading', `Yükleniyor (0/${this.totalChunks})`);

            // 2. CHUNK'LARI SIRAYLA YÜKLE
            for (let i = 0; i < this.totalChunks; i++) {
                if (this.cancelled) {
                    await this._cancelOnServer();
                    return;
                }

                await this._uploadChunkWithRetry(i);
                this.uploadedChunks++;

                const percent = Math.round((this.uploadedChunks / this.totalChunks) * 100);
                this.onProgress(percent);
                this.onStatusChange('uploading', `Yükleniyor (${this.uploadedChunks}/${this.totalChunks})`);
            }

            // 3. FINALIZE
            this.onStatusChange('finalizing', 'Birleştiriliyor...');

            const finalResp = await axios.post(
                this.endpoints.finalize.replace('{uploadId}', this.uploadId),
                {
                    total_chunks: this.totalChunks,
                    page_slug:    this.pageSlug,
                },
                { headers: { 'X-CSRF-TOKEN': this.csrfToken } }
            );

            if (finalResp.data.status !== 'success') {
                throw new Error(finalResp.data.message || 'Birleştirme başarısız.');
            }

            this.onStatusChange('complete', 'Tamamlandı ✓');
            this.onComplete(finalResp.data.filename, finalResp.data.url);

        } catch (err) {
            if (! this.cancelled) {
                const msg = err.response?.data?.message || err.message || 'Bilinmeyen hata';
                this.onStatusChange('error', 'Hata: ' + msg);
                this.onError(msg);
            }
        }
    }

    cancel() {
        this.cancelled = true;
        if (this.cancelTokenSrc) {
            this.cancelTokenSrc.cancel('İptal edildi');
        }
        this.onStatusChange('cancelled', 'İptal edildi');
        this._cancelOnServer();
    }

    async _cancelOnServer() {
        if (! this.uploadId) return;
        try {
            await axios.delete(
                this.endpoints.cancel.replace('{uploadId}', this.uploadId),
                { headers: { 'X-CSRF-TOKEN': this.csrfToken } }
            );
        } catch (e) { /* sessizce yut */ }
    }

    async _uploadChunkWithRetry(index) {
        let lastErr;
        for (let attempt = 1; attempt <= this.maxRetries; attempt++) {
            try {
                return await this._uploadChunk(index);
            } catch (err) {
                lastErr = err;
                if (this.cancelled) throw err;
                if (attempt < this.maxRetries) {
                    this.onStatusChange('retrying',
                        `Chunk ${index + 1} yeniden deneniyor (${attempt}/${this.maxRetries})...`);
                    await new Promise(r => setTimeout(r, this.retryDelay * attempt));
                }
            }
        }
        throw lastErr;
    }

    _uploadChunk(index) {
        const start = index * this.chunkSize;
        const end   = Math.min(start + this.chunkSize, this.file.size);
        const blob  = this.file.slice(start, end);

        const formData = new FormData();
        formData.append('chunk_index', index);
        formData.append('chunk', blob, `${index}.part`);

        this.cancelTokenSrc = axios.CancelToken.source();

        return axios.post(
            this.endpoints.chunk.replace('{uploadId}', this.uploadId),
            formData,
            {
                headers: {
                    'X-CSRF-TOKEN':  this.csrfToken,
                    'Content-Type':  'multipart/form-data',
                },
                cancelToken: this.cancelTokenSrc.token,
            }
        ).then(resp => {
            if (resp.data.status !== 'success') {
                throw new Error(resp.data.message || 'Chunk yüklenemedi.');
            }
            return resp.data;
        });
    }
}

window.ChunkedVideoUploader = ChunkedVideoUploader;
