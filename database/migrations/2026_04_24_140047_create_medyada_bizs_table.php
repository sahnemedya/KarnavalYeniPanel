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
        Schema::create('medyada_bizs', function (Blueprint $table) {
            $table->id();
            $table->string('aciklama')->charset('utf8')->collation('utf8_general_ci')->default(NULL)->nullable();
            $table->string('kaynak')->charset('utf8')->collation('utf8_general_ci');
            $table->string('url')->charset('utf8')->collation('utf8_general_ci')->nullable()->default(NULL);
            $table->string('resim')->charset('utf8')->collation('utf8_general_ci');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medyada_bizs');
    }
};
