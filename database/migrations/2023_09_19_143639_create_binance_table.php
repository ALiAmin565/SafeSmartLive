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


        Schema::create('binance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('symbol');
            $table->string('type');
            $table->string('side');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('stop_price');
            $table->string('status');
            $table->string('orderID');
            $table->json('massageError');
            $table->unsignedBigInteger('recomondations_id');
            $table->foreign('recomondations_id')->references('id')->on('recomondations');
            $table->string('respone');
            $table->double('buy_price_sell');
            $table->unsignedBigInteger('bot_num');
            $table->foreign('bot_num')->references('id')->on('bots');
            $table->double('profit_usdt');
            $table->double('profit_per');
            $table->double('fees');
            $table->integer('status_fees');
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
        Schema::dropIfExists('binance');
    }
};
