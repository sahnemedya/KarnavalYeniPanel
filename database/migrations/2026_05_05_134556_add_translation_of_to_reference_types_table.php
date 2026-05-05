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
        Schema::table('reference_types', function (Blueprint $table) {
            $table->foreignId('translation_of')
                ->nullable()
                ->after('lang_id')
                ->constrained('reference_types')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reference_types', function (Blueprint $table) {
            $table->dropForeign(['translation_of']);
            $table->dropColumn('translation_of');
        });
    }
};
