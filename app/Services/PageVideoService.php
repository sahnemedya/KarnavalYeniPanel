<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageVideo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class PageVideoService
{
    protected CommonService $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    // ===========================
    // CRUD - Extra Sayfasında Kullanılan
    // ===========================

    /**
     * Bir sayfaya ait tüm videoları getir (admin paneli ve frontend için).
     */
    public function getVideosForPage(int $pageId, bool $onlyActive = false): Collection
    {
        $query = PageVideo::where('page_id', $pageId)->ordered();

        if ($onlyActive) {
            $query->active();
        }

        return $query->get();
    }

    /**
     * Yeni video ekle (extra.blade içindeki "Video Ekle" formundan).
     */
    public function store(Request $request, int $pageId): array
    {
        $status  = "success";
        $message = "Video Eklendi.";

        try {
            $page = Page::findOrFail($pageId);

            $sourceType  = $request->input('source_type', 'youtube'); // youtube | local
            $sourceValue = trim($request->input('source_value', ''));

            if ($sourceType === 'youtube') {
                $sourceValue = $this->extractYoutubeId($sourceValue);
            } else {
                // local: dosya zaten async upload ile yüklendi, source_value = filename
                if (! $sourceValue) {
                    throw new InvalidArgumentException('Lütfen bir video dosyası yükleyin.');
                }
            }

            $video = PageVideo::create([
                'page_id'      => $page->id,
                'title'        => $request->input('title'),
                'source_type'  => $sourceType,
                'source_value' => $sourceValue,
                'cover_image'  => $request->input('cover_image'), // async upload'tan gelir
                'sort_order'   => $this->getNextSortOrder($page->id),
                'is_active'    => $request->boolean('is_active', true),
            ]);

            LogService::add("Page Video Service Store", $status, $message);
            return ["status" => $status, "message" => $message, "video" => $video];

        } catch (\Throwable $exception) {
            $status  = "error";
            $message = "Video Eklenemedi: " . $exception->getMessage();
            LogService::add("Page Video Service Store", $status, $message);
            return ["status" => $status, "message" => $message];
        }
    }

    /**
     * Video güncelle.
     */
    public function update(Request $request, int $videoId): array
    {
        $status  = "success";
        $message = "Video Güncellendi.";

        try {
            $video = PageVideo::findOrFail($videoId);

            $sourceType  = $request->input('source_type', $video->source_type);
            $sourceValue = trim($request->input('source_value', $video->source_value));

            if ($sourceType === 'youtube') {
                $sourceValue = $this->extractYoutubeId($sourceValue);

                // Eğer önceden local idi ve şimdi youtube'a çevriliyorsa, eski dosyayı sil
                if ($video->source_type === 'local' && $video->source_value) {
                    $this->commonService->deleteFile(
                        config('constants.video_path'),
                        $video->source_value
                    );
                }
            } else {
                // local: yeni dosya yüklendiyse eskiyi sil
                if (! $sourceValue) {
                    throw new InvalidArgumentException('Video dosyası gerekli.');
                }
                if ($video->source_type === 'local'
                    && $video->source_value
                    && $video->source_value !== $sourceValue) {
                    $this->commonService->deleteFile(
                        config('constants.video_path'),
                        $video->source_value
                    );
                }
            }

            $updateData = [
                'title'        => $request->input('title', $video->title),
                'source_type'  => $sourceType,
                'source_value' => $sourceValue,
                'is_active'    => $request->boolean('is_active', $video->is_active),
            ];

            // Yeni kapak yüklendiyse eskiyi sil
            $newCover = $request->input('cover_image');
            if ($newCover && $newCover !== $video->cover_image) {
                if ($video->cover_image) {
                    $this->commonService->deleteFile(
                        config('constants.video_path'),
                        $video->cover_image
                    );
                }
                $updateData['cover_image'] = $newCover;
            }

            $video->update($updateData);

            LogService::add("Page Video Service Update", $status, $message);
            return ["status" => $status, "message" => $message, "video" => $video->fresh()];

        } catch (\Throwable $exception) {
            $status  = "error";
            $message = "Video Güncellenemedi: " . $exception->getMessage();
            LogService::add("Page Video Service Update", $status, $message);
            return ["status" => $status, "message" => $message];
        }
    }

    /**
     * Video sil (soft delete + dosya temizliği).
     */
    public function destroy(int $videoId): array
    {
        $status  = "success";
        $message = "Video Silindi.";

        try {
            $video = PageVideo::findOrFail($videoId);

            // Local video dosyası varsa sil
            if ($video->source_type === 'local' && $video->source_value) {
                $this->commonService->deleteFile(
                    config('constants.video_path'),
                    $video->source_value
                );
            }

            // Kapak görseli varsa sil
            if ($video->cover_image) {
                $this->commonService->deleteFile(
                    config('constants.video_path'),
                    $video->cover_image
                );
            }

            $video->forceDelete(); // tamamen sil

            LogService::add("Page Video Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];

        } catch (\Throwable $exception) {
            $status  = "error";
            $message = "Video Silinemedi: " . $exception->getMessage();
            LogService::add("Page Video Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        }
    }

    /**
     * Sürükle-bırak sıralama.
     */
    public function reorder(array $orderedIds): array
    {
        try {
            DB::transaction(function () use ($orderedIds) {
                foreach ($orderedIds as $index => $id) {
                    PageVideo::where('id', $id)->update(['sort_order' => $index + 1]);
                }
            });

            LogService::add("Page Video Service Reorder", "success", "Sıralama güncellendi.");
            return ["status" => "success", "message" => "Sıralama güncellendi."];
        } catch (\Throwable $e) {
            LogService::add("Page Video Service Reorder", "error", $e->getMessage());
            return ["status" => "error", "message" => "Sıralama güncellenemedi."];
        }
    }

    // ===========================
    // CHUNKED UPLOAD (Büyük dosyalar için)
    // ===========================

    /**
     * Yeni bir parçalı yükleme oturumu başlat.
     */
    public function initChunkedUpload(Request $request): array
    {
        $request->validate([
            'filename'   => 'required|string|max:255',
            'total_size' => 'required|integer|min:1',
            'mime_type'  => 'required|string|max:100',
        ]);

        // Boyut kontrolü (max 1GB)
        $maxSize = (int) config('constants.video_max_size', 1024 * 1024 * 1024);
        if ($request->total_size > $maxSize) {
            return [
                "status"  => "error",
                "message" => "Dosya çok büyük. Maksimum " . $this->humanSize($maxSize),
            ];
        }

        // MIME kontrolü
        $allowed = ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-matroska'];
        if (! in_array($request->mime_type, $allowed, true)) {
            return [
                "status"  => "error",
                "message" => "Geçersiz dosya türü. Sadece mp4, webm, mov, mkv kabul edilir.",
            ];
        }

        $uploadId = (string) Str::uuid();
        $chunkDir = "video-chunks/{$uploadId}";

        Storage::disk('local')->makeDirectory($chunkDir);
        Storage::disk('local')->put("{$chunkDir}/meta.json", json_encode([
            'filename'   => $request->filename,
            'total_size' => $request->total_size,
            'mime_type'  => $request->mime_type,
            'created_at' => now()->toIso8601String(),
            'received'   => [],
        ]));

        return [
            "status"     => "success",
            "upload_id"  => $uploadId,
            "chunk_size" => 5 * 1024 * 1024, // 5MB
        ];
    }

    /**
     * Bir chunk kaydet.
     */
    public function storeChunk(Request $request, string $uploadId): array
    {
        try {
            $chunkIndex = (int) $request->input('chunk_index');
            $chunk      = $request->file('chunk');

            if (! $chunk) {
                throw new InvalidArgumentException('Chunk dosyası eksik.');
            }

            $chunkDir = "video-chunks/{$uploadId}";
            $this->assertUploadExists($chunkDir);

            // Chunk'ı kaydet
            Storage::disk('local')->putFileAs(
                $chunkDir,
                $chunk,
                "{$chunkIndex}.part"
            );

            // Meta'yı güncelle
            $meta = json_decode(Storage::disk('local')->get("{$chunkDir}/meta.json"), true);
            if (! in_array($chunkIndex, $meta['received'], true)) {
                $meta['received'][] = $chunkIndex;
                sort($meta['received']);
                Storage::disk('local')->put("{$chunkDir}/meta.json", json_encode($meta));
            }

            return [
                "status"   => "success",
                "received" => count($meta['received']),
            ];

        } catch (\Throwable $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Chunk'ları birleştir, video-uploads klasörüne final dosyayı yaz, dosya adını döndür.
     * Dönen filename'i form'a hidden input olarak yaz, sonra store/update'te kullan.
     */
    public function finalizeChunkedUpload(Request $request, string $uploadId): array
    {
        try {
            $totalChunks = (int) $request->input('total_chunks');
            $pageSlug    = $request->input('page_slug', 'video');

            $chunkDir = "video-chunks/{$uploadId}";
            $this->assertUploadExists($chunkDir);

            $meta = json_decode(Storage::disk('local')->get("{$chunkDir}/meta.json"), true);

            if (count($meta['received']) !== $totalChunks) {
                throw new RuntimeException(sprintf(
                    'Eksik chunk: beklenen %d, gelen %d',
                    $totalChunks,
                    count($meta['received'])
                ));
            }

            // Final dosya adını üret (page_slug + random + extension)
            $extension = pathinfo($meta['filename'], PATHINFO_EXTENSION) ?: 'mp4';
            $safeSlug  = Str::slug($pageSlug);
            $filename  = "{$safeSlug}-video-" . Str::lower(Str::random(6)) . "." . $extension;

            // public diskte video_path altına yaz
            $finalRelative = config('constants.video_path') . "/" . $filename;
            $finalAbsolute = Storage::disk('public')->path($finalRelative);

            if (! is_dir(dirname($finalAbsolute))) {
                mkdir(dirname($finalAbsolute), 0755, true);
            }

            // Chunk'ları sırayla birleştir
            $finalHandle = fopen($finalAbsolute, 'wb');
            if (! $finalHandle) {
                throw new RuntimeException('Final dosya açılamadı.');
            }

            try {
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = Storage::disk('local')->path("{$chunkDir}/{$i}.part");
                    if (! file_exists($chunkPath)) {
                        throw new RuntimeException("Chunk {$i} bulunamadı.");
                    }
                    $chunkHandle = fopen($chunkPath, 'rb');
                    stream_copy_to_stream($chunkHandle, $finalHandle);
                    fclose($chunkHandle);
                }
            } finally {
                fclose($finalHandle);
            }

            // Geçici dosyaları temizle
            Storage::disk('local')->deleteDirectory($chunkDir);

            return [
                "status"   => "success",
                "filename" => $filename,
                "url"      => asset("storage/" . $finalRelative),
            ];

        } catch (\Throwable $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Yarım kalan upload'u temizle.
     */
    public function cancelChunkedUpload(string $uploadId): array
    {
        Storage::disk('local')->deleteDirectory("video-chunks/{$uploadId}");
        return ["status" => "success"];
    }

    /**
     * Async kapak görseli yükleme (mevcut asyncUpload pattern'i ile aynı).
     * Doğrudan tek seferde yüklenir, küçük dosya olduğu için chunked'a gerek yok.
     */
    public function uploadCoverAsync(Request $request): array
    {
        try {
            $file     = $request->file('file_data');
            $pageSlug = $request->input('slug', 'video');

            if (! $file) {
                throw new InvalidArgumentException('Görsel dosyası eksik.');
            }

            $extension = $file->getClientOriginalExtension();
            $filename  = Str::slug($pageSlug) . '-cover-' . Str::lower(Str::random(4)) . '.' . $extension;

            $this->commonService->uploadFile(
                config('constants.video_path'),
                $file,
                $filename
            );

            return [
                "status"   => "success",
                "filename" => $filename,
                "url"      => asset("storage/" . config('constants.video_path') . "/" . $filename),
            ];
        } catch (\Throwable $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Yüklenmiş ama henüz kaydedilmemiş geçici dosyayı sil
     * (kullanıcı formdan vazgeçerse veya iptal ederse).
     */
    public function deleteTempFile(string $filename): array
    {
        try {
            $this->commonService->deleteFile(config('constants.video_path'), $filename);
            return ["status" => "success"];
        } catch (\Throwable $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    // ===========================
    // YARDIMCI METODLAR
    // ===========================

    /**
     * YouTube URL'inden ID çıkar.
     */
    public function extractYoutubeId(string $input): string
    {
        $input = trim($input);

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }
        if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $input, $m)) {
            return $m[1];
        }
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $input, $m)) {
            return $m[1];
        }
        if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/', $input, $m)) {
            return $m[1];
        }

        throw new InvalidArgumentException("Geçerli bir YouTube URL'i veya ID değil: {$input}");
    }

    protected function getNextSortOrder(int $pageId): int
    {
        return (int) PageVideo::where('page_id', $pageId)->max('sort_order') + 1;
    }

    protected function assertUploadExists(string $chunkDir): void
    {
        if (! Storage::disk('local')->exists("{$chunkDir}/meta.json")) {
            throw new RuntimeException('Yükleme oturumu bulunamadı veya süresi dolmuş.');
        }
    }

    protected function humanSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
