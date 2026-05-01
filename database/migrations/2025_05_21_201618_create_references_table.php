<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sezon_id')->constrained('karnaval_sezonus')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('reference_types')->nullOnDelete();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->integer('hit')->nullable()->default(0);
            $table->boolean('published')->nullable()->default(0);
            $table->boolean('show_homepage')->nullable()->default(0);
            $table->foreignId('lang_id')->nullable()->default(1)->constrained('languages');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
