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
        Schema::create('portakalli_lezzetlers', function (Blueprint $table) {
            $table->id();
            $table->string("ad")->nullable();
            $table->string("tc")->nullable();
            $table->string("dogum")->nullable();
            $table->string("adres")->nullable();
            $table->string("telefon")->nullable();
            $table->string("mail")->nullable();
            $table->string("meslek")->nullable();
            $table->string("yemekadi")->nullable();
            $table->string("yemekmalzemesi")->nullable();
            $table->string("yemektarifi")->nullable();
            $table->string("bilgi")->nullable();
            $table->boolean("markRead")->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portakalli_lezzetlers');
    }
};
