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
        Schema::create('contact_forms', function (Blueprint $table) {
            $table->id();
            $table->text('formAdi')->charset('utf8')->collation('utf8_general_ci');
            $table->text('adSoyad')->charset('utf8')->collation('utf8_general_ci');
            $table->text('telefon')->charset('utf8')->collation('utf8_general_ci');
            $table->text('cv')->nullable()->charset('utf8')->collation('utf8_general_ci');
            $table->text('email')->charset('utf8')->collation('utf8_general_ci');
            $table->text('konu')->charset('utf8')->nullable()->collation('utf8_general_ci');
            $table->longText('mesaj')->charset('utf8')->nullable()->collation('utf8_general_ci');
            $table->boolean('markRead')->default(0)->nullable();
            $table->boolean('izin')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
