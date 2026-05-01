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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->charset('utf8')->collation('utf8_general_ci');
            $table->string('note', 100)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('image', 150)->nullable();
            $table->boolean('show_menu')->nullable()->default(0);
            $table->boolean('show_homepage')->nullable()->default(0);
            $table->boolean('show_footer')->nullable()->default(0);
            $table->boolean('show_panel')->nullable()->default(0);
            $table->boolean('is_medical_unit')->nullable()->default(0);
            $table->boolean('is_doctors')->nullable()->default(0);
            $table->integer('hit')->nullable();
            $table->foreignId("parent_category")->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId("translation_of")->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId("lang_id")->nullable()->constrained('languages')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->fullText(['name', 'note']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
