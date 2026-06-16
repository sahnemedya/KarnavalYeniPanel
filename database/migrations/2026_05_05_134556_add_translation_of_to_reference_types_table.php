<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('reference_types', 'translation_of')) {
            Schema::table('reference_types', function (Blueprint $table) {
                $table->unsignedBigInteger('translation_of')->nullable()->after('lang_id');
            });
        }
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
