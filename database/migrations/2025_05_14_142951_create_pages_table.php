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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('hit')->nullable();
            $table->foreignId('sezon_id')->nullable()->constrained('karnaval_sezonus')->onDelete('set null');
            $table->string('title',100);
            $table->string('inside_title',150)->nullable();
            $table->string('slug',150)->unique();
            $table->string('link',500)->nullable();
            $table->string('link2',500)->nullable();
            $table->string('link3',500)->nullable();
            $table->string('heyzen',500)->nullable();
            $table->longText('content',5000)->nullable();
            $table->text('location')->nullable();
            $table->string('video', 500)->nullable();
            $table->string('ses', 500)->nullable();
            $table->string('spotify', 500)->nullable();
//            $table->string('video', 500)->nullable();
            $table->string('image',100)->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('file')->nullable();
            $table->boolean('is_main')->nullable()->default(0);
            $table->foreignId('blade_id')->nullable()->constrained('blades')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('translation_of')->nullable()->constrained('pages')->nullOnDelete();
            $table->foreignId('parent_page')->nullable()->constrained('pages')->nullOnDelete();
            $table->foreignId('lang_id')->nullable()->default(1)->constrained('languages')->nullOnDelete();
            $table->boolean('form_active')->nullable()->default(0);
            $table->boolean('published')->default(true);
            $table->boolean('show_homepage')->nullable()->default(0);
            $table->boolean('show_footer')->nullable()->default(0);
            $table->boolean('show_sponsorluk')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
