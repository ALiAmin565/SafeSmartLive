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
    
        Schema::create('binance_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('symbol');
            $table->string('side');
            $table->integer('quantity');
            $table->integer('price')->nullable();
            $table->integer('stop_price')->nullable();
            $table->string('status');
            $table->string('orderID')->nullable();
            $table->string('massageError')->nullable();
            $table->unsignedBigInteger('recomondations_id');
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
        Schema::dropIfExists('binance_users');
    }
};
