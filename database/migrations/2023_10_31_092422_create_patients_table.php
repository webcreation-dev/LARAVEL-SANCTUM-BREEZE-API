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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('email')->nullable();
            
            $table->string('treatment');
            $table->string('phone_number');
            $table->string('frame');
            $table->string('reference');
            $table->string('color');
            $table->string('price');
            $table->string('montant_verre');
            $table->string('left_eye_vl_correction');
            $table->string('left_eye_vp_correction');
            $table->string('right_eye_vl_correction');
            $table->string('right_eye_vp_correction');
            $table->dateTime('date_save');
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
        Schema::dropIfExists('patients');
    }
};
