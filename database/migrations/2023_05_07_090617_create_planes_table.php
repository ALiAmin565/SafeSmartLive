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
        Schema::create('planes', function (Blueprint $table) {
            $table->id();

            $table->string('nameChannel')->nullable()->default('free');
            $table->string('name');

            $table->integer('discount')->nullable()->default(0);
            $table->integer('price');
            $table->string('percentage1');
            $table->string('percentage2')->nullable();
            $table->string('percentage3')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
