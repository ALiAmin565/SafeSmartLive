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
        Schema::create('recommendation__plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planes_id');
            $table->unsignedBigInteger('recomondations_id');
            $table->foreign('planes_id')->references('id')->on('planes');
            $table->foreign('recomondations_id')->references('id')->on('recomondations');

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
        Schema::dropIfExists('recommendation__plans');
    }
};
