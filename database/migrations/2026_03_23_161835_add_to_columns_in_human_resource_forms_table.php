<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('human_resource_forms', function (Blueprint $table) {
            $table->unsignedInteger('spam_score')->default(0)->after('izin');
            $table->boolean('is_spam')->default(false)->after('spam_score');
            $table->text('spam_reasons')->nullable()->after('is_spam');
            $table->string('ip_address', 45)->nullable()->after('spam_reasons');
            $table->text('user_agent')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('human_resource_forms', function (Blueprint $table) {
            $table->dropColumn([
                'spam_score',
                'is_spam',
                'spam_reasons',
                'ip_address',
                'user_agent',
            ]);
        });
    }
};
