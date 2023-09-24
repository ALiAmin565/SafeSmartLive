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
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin');
            $table->foreign('admin')->references('id')->on('users');

            $table->double('buy_price');
            $table->json('users');
            $table->integer('status');
            $table->double('stoplose');
            $table->json('targets');
            $table->string('ticker');
            $table->json('entry');
            $table->json('tradeinfo');
            $table->unsignedBigInteger('recomondations_id');
            $table->foreign('recomondations_id')->references('id')->on('recomondations');
            $table->unsignedBigInteger('bot_num');
            $table->foreign('bot_num')->references('id')->on('bots');
            $table->integer('last_tp');
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
        Schema::dropIfExists('experts');
    }
};
