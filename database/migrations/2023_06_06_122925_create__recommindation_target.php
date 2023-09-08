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
        Schema::create('_recommindation_target', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recomondations_id');
            $table->foreign('recomondations_id')->references('id')->on('recomondations');
            $table->string('target');
            $table->softDeletes();
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
        Schema::dropIfExists('_recommindation_target');
    }
};
