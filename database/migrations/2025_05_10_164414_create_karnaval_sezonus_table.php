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
        Schema::create('karnaval_sezonus', function (Blueprint $table) {
            $table->id();
            $table->integer('hit')->nullable();
            $table->date('karnaval_tarihi_baslangic');
            $table->date('karnaval_tarihi_bitis');
            $table->date('sezon_baslangici');
            $table->string('karnaval_yili',100);
            $table->boolean('published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karnaval_sezonus');
    }
};
