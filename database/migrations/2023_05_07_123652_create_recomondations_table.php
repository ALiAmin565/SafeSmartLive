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
        Schema::create('recomondations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('desc')->nullable();
            $table->string('currency');
            $table->string('entry_price');
            $table->string('stop_price');
            $table->boolean('archive');
            $table->boolean('active');
            $table->string('img');
            $table->integer('number_show')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('planes_id');
            $table->foreign('planes_id')->references('id')->on('planes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recomondations');
    }
};
