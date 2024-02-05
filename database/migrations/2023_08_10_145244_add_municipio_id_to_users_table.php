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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('municipio_id')->nullable()->after('password');
        $table->foreign('municipio_id')->references('id')->on('municipios')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['municipio_id']);
        $table->dropColumn('municipio_id');
    });
}

};
