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
        Schema::create('telgram_groups', function (Blueprint $table) {
            $table->id();
            $table->text('merchant');
            $table->text('token');
            $table->bigInteger('plan_id')->unsigned();
            $table->foreign('plan_id')->references('id')->on('planes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telgram_groups');
    }
};
