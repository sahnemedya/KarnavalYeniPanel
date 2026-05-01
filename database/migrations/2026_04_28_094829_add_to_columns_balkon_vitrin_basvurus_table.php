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
        Schema::table('balkon_vitrin_basvurus', function (Blueprint $table) {
            $table->unsignedInteger('spam_score')->default(0)->after('kvkk_onay');
            $table->boolean('is_spam')->default(false)->after('spam_score');
            $table->text('spam_reasons')->nullable()->after('is_spam');
            $table->text('user_agent')->nullable()->after('ip_adresi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balkon_vitrin_basvurus', function (Blueprint $table) {
            $table->dropColumn([
                'spam_score',
                'is_spam',
                'spam_reasons',
                'user_agent',
            ]);
        });
    }
};




