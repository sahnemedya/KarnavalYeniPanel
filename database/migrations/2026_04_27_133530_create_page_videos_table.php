<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_videos', function (Blueprint $table) {
            $table->id();

            // Hangi sayfaya ait?
            $table->foreignId('page_id')
                ->constrained('pages')
                ->cascadeOnDelete();

            // Video başlığı (h2 içinde gösterilecek)
            $table->string('title');

            // Kaynak türü:
            //  - youtube: YouTube ID veya URL
            //  - local:   Sunucuda tutulan dosya adı (config('constants.video_path') altında)
            $table->enum('source_type', ['youtube', 'local'])->default('youtube');

            // YouTube ID veya local dosya adı (örn: rquCmuSlHeU veya video-slug-abc.mp4)
            $table->string('source_value');

            // Kapak görseli dosya adı (config('constants.video_path') altında saklanır)
            $table->string('cover_image')->nullable();

            // Sıralama (DataTables benzeri sürükle-bırak için)
            $table->unsignedInteger('sort_order')->default(0);

            // Aktif mi?
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['page_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_videos');
    }
};
