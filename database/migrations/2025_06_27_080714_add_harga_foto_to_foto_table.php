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
    Schema::table('foto', function (Blueprint $table) {
        $table->integer('harga_foto')->default(0)->after('file_foto');
    });
}

public function down()
{
    Schema::table('foto', function (Blueprint $table) {
        $table->dropColumn('harga_foto');
    });
}

};
