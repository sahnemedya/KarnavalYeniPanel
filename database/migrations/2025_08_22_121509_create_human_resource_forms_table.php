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
        Schema::create('human_resource_forms', function (Blueprint $table) {
            $table->id();
            $table->text('adSoyad')->charset('utf8')->collation('utf8_general_ci');
            $table->text('telefon')->charset('utf8')->collation('utf8_general_ci');
            $table->text('email')->charset('utf8')->collation('utf8_general_ci');
            $table->text('cvAdi')->nullable()->charset('utf8')->collation('utf8_general_ci');
            $table->boolean('ik')->default(0)->nullable();
            $table->longText('mesaj')->charset('utf8')->collation('utf8_general_ci');
            $table->boolean('kvkk')->default(0)->nullable();
            $table->boolean('markRead')->default(0)->nullable();
            $table->boolean('izin')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_resource_forms');
    }
};
