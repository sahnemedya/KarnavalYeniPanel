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
        Schema::create('bulten_iletisim_formus', function (Blueprint $table) {
            $table->id();
            $table->text('adSoyad')->charset('utf8')->collation('utf8_general_ci');
            $table->text('telefon')->charset('utf8')->collation('utf8_general_ci');
            $table->text('konu')->nullable()->change();
            $table->longText('mesaj')->nullable()->change();
            $table->text('email')->nullable()->change();
            $table->boolean('markRead')->default(0)->nullable();
            $table->boolean('izin')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulten_iletisim_formus');
    }
};
