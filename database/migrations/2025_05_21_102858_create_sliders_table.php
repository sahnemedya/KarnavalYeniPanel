<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title',100);
            $table->string('description')->nullable();
            $table->string('image',100)->nullable();
            $table->string('mobile_image',100)->nullable();
            $table->string('url',150)->nullable();
            $table->boolean('published')->nullable()->default(false);
            $table->integer('hit')->nullable()->default(0);
            $table->foreignId("lang_id")->nullable()->default(1)->constrained("languages")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
