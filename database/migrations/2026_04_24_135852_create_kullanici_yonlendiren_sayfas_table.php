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
        Schema::create('kullanici_yonlendiren_sayfas', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('yonlendiren_site')->nullable();
            $table->string('utmSource')->nullable();
            $table->string('utmMedium')->nullable();
            $table->string('utmCampaign')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kullanici_yonlendiren_sayfas');
    }
};
