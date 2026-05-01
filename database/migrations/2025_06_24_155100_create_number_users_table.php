<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('number_users', function (Blueprint $table) {
            $table->id();
            $table->string('ip',15);
            $table->string('cihaz',15)->nullable()->charset('utf8')->collation('utf8_general_ci');
            $table->string('ulke', 100)->nullable();
            $table->string('sehir', 100)->nullable();
            $table->string('ulke_kodu', 5)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('number_users');
    }
};
