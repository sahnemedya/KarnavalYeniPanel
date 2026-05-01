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
        Schema::create('yetkili_ajanslars', function (Blueprint $table) {
            $table->id();
            $table->string("logo");
            $table->string("adi");
            $table->string("telefon")->nullable();
            $table->string("telefon2")->nullable();
            $table->string("mail")->nullable();
            $table->string("mail2")->nullable();
            $table->string("link")->nullable();
            $table->string("yetki")->nullable();
            $table->unsignedBigInteger('karnaval_sezonu')->nullable()->default(NULL);
            $table->boolean("aktif")->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('karnaval_sezonu')->references('id')->on('karnaval_sezonus');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yetkili_ajanslars');
    }
};
