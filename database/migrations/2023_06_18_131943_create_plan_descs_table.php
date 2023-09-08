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
        Schema::create('plan_descs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('planes');
            $table->string('desc');

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
        Schema::dropIfExists('plan_descs');
    }
};
