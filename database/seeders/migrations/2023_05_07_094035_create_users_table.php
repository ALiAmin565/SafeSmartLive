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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('verified')->default(false);

            $table->string('password');
            $table->rememberToken();
            $table->integer('phone')->nullable();
            $table->string('state')->default('user');;
            $table->bigInteger('plan_id')->unsigned();
            $table->foreign('plan_id')->references('id')->on('planes');
            $table->string('payment_method');
            $table->boolean('banned')->default(false);

            $table->date('strat_plan')->nullable();
            $table->date('end_plan')->nullable();
            $table->string('comming_afflite')->nullable();
            $table->string('percentage')->nullable();
            $table->string('discount')->nullable();
            $table->boolean('boned')->nullable();
            $table->string('affiliate_code')->nullable();
            $table->string('affiliate_link')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->string('plan')->default("free");


            $table->text('fcm_token')->nullable();
            $table->string('image_profile')->nullable();
            $table->string('image_payment')->nullable();
            $table->integer('number_points')->nullable();
            $table->integer('money')->nullable();
            $table->integer('otp')->nullable();
            $table->integer('number_of_user')->nullable();
            $table->timestamps();

            //

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
