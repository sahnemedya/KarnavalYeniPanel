<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contact_forms', function (Blueprint $table) {
            $table->text('konu')->nullable()->change();
            $table->longText('mesaj')->nullable()->change();
            $table->text('email')->nullable()->change();


        });
    }

    public function down()
    {
        Schema::table('contact_forms', function (Blueprint $table) {
            $table->text('konu')->nullable(false)->change();
            $table->longText('mesaj')->nullable(false)->change();
            $table->text('email')->nullable(false)->change();

        });
    }
};
