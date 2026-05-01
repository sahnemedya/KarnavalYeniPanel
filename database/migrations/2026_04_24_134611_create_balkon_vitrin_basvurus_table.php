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
        Schema::create('balkon_vitrin_basvurus', function (Blueprint $table) {
            $table->id();
            $table->enum('tur', ['vitrin', 'balkon']);
            $table->string('isletme_adi')->nullable();
            $table->string('ad_soyad');
            $table->string('email');
            $table->string('telefon');
            $table->text('adres');
            $table->boolean('resit_mi')->default(1);
            $table->string('veli_izin_belgesi')->nullable();
            $table->text('fotograflar')->nullable(); // JSON veya Virgüllü string formatında yollar
            $table->boolean('kvkk_onay')->default(0);
            $table->string('ip_adresi')->nullable();
            $table->boolean('markRead')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balkon_vitrin_basvurus');
    }
};
