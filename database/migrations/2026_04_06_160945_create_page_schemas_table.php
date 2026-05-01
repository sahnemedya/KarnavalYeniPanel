<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_schemas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            // Örn: Physician, MedicalProcedure, Article, FAQPage, VideoObject
            $table->string('schema_type')->default('WebPage');
            // Sayfaya özel ek özellikler (JSON olarak tutulabilir: Hazırlık süresi, Riskler vb.)
            $table->json('additional_data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_schemas');
    }
};
