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
        Schema::create('bot_orders', function (Blueprint $table) {
            $table->id();
            // $table->
            // protected $fillable=['bot_id','symbol','side','price','stop_price','recomondations_id','buy_price_sell'];
            $table->unsignedBigInteger('bot_id');
            $table->foreign('bot_id')->references('id')->on('bots');

            $table->string('symbol');
            $table->string('side');
            $table->string('price');
            $table->unsignedBigInteger('recomondations_id');
            $table->foreign('recomondations_id')->references('id')->on('recomondations');
                     $table->string('buy_price_sell');
            $table->string('stop_price');
            $table->string('takeprofit');

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
        Schema::dropIfExists('bot_orders');
    }
};
