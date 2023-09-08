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
        Schema::create('plan_telgram_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planes_id');
            $table->unsignedBigInteger('telgram_groups_id');
            $table->foreign('planes_id')->references('id')->on('planes');
            $table->foreign('telgram_groups_id')->references('id')->on('telgram_groups');
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
        Schema::dropIfExists('plan_telgram_group');
    }
};
