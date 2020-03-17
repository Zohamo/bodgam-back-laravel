<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->string('name', 128);
            $table->string('email')->unique();

            // Details

            $table->binary('avatar')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable();
            $table->longText('description')->nullable();
            $table->dateTimeTz('birthdate')->nullable();
            $table->string('bggName', 128)->nullable();
            $table->string('phoneNumber', 48)->nullable();
            $table->string('website', 128)->nullable();

            // Address

            $table->string('district', 64)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('country', 3)->nullable();

            // FK

            $table->unsignedBigInteger('userId');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');

            // Timestamps

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
