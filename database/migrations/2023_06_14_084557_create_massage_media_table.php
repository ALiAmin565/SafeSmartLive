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
    public function up()
    {
        Schema::create('massage_media', function (Blueprint $table) {
            $table->id();
            $table->string('img')->nullable();
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->unsignedBigInteger('massage_id');
            $table->foreign('massage_id')->references('id')->on('massages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('massage_media');
    }
};
