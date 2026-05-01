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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("description")->nullable();
            $table->string('slug', 150)->unique();
            $table->longText('content', 5000)->nullable();
            $table->foreignId("medical_unit")->constrained("pages")->onDelete("no action");
            $table->foreignId("medical_unit2")->nullable()->constrained("pages")->onDelete("no action");
            $table->string("image")->nullable();
            $table->string("image2")->nullable();
            $table->boolean('show')->nullable()->default(1);
            $table->boolean('show_homepage')->nullable()->default(0);
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
        Schema::dropIfExists('doctors');
    }
};
