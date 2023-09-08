<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('active');
            $table->integer('limit');
            $table->integer('used');
            $table->string('code');
            $table->boolean('type');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('plan_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('plan_id')->references('id')->on('planes');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
