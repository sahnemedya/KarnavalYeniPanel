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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('content');
            $table->string('image',100)->nullable();
            $table->integer('hit')->nullable()->default(0);
            $table->boolean('published')->nullable()->default(0);
            $table->foreignId('lang_id')->nullable()->default(1)->constrained('languages')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
