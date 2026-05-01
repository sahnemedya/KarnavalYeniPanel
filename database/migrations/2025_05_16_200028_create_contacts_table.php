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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email',100)->nullable();
            $table->string('email2',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('phone2',20)->nullable();
            $table->string('address',255)->nullable();
            $table->string('country',50)->nullable();
            $table->string('city',50)->nullable();
            $table->string('state',50)->nullable();
            $table->string('person',75)->nullable();
            $table->string('map',1000)->nullable();
            $table->string('map_link', 1000)->nullable();
            $table->string('hizmet')->nullable();
            $table->date('tarih')->nullable();
            $table->time('saat')->nullable();
            $table->integer('hit')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
