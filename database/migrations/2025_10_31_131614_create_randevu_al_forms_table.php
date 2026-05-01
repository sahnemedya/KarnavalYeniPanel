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
        Schema::create('randevu_al_forms', function (Blueprint $table) {
            $table->id();
            $table->text('adSoyad')->charset('utf8')->collation('utf8_general_ci');
            $table->text('telefon')->charset('utf8')->collation('utf8_general_ci');
            $table->text('email')->charset('utf8')->collation('utf8_general_ci');
            $table->text('birim')->nullable()->charset('utf8')->collation('utf8_general_ci');
            $table->text('doktor')->nullable()->charset('utf8')->collation('utf8_general_ci');
            $table->date('tarih')->nullable(); // Kullanıcının seçtiği randevu GÜNÜ
            $table->time('saat')->nullable();  // Kullanıcının seçtiği randevu SAATİ
            $table->longText('mesaj')->charset('utf8')->collation('utf8_general_ci');
            $table->boolean('markRead')->default(0)->nullable();
            $table->boolean('kvkk')->default(0)->nullable();
            $table->boolean('izin')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('randevu_al_forms');
    }
};
