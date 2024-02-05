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
    Schema::create('files', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('path');
        $table->string('type')->nullable();
        $table->bigInteger('size')->nullable();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('survey_id');
        $table->timestamps();
        
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
