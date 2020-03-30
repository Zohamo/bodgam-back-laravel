<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilePrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_privacy', function (Blueprint $table) {
            $table->boolean('bggName')->default(0);
            $table->boolean('birthdate')->default(0);
            $table->boolean('email')->default(0);
            $table->boolean('phoneNumber')->default(0);
            $table->boolean('website')->default(0);

            // FK

            $table->unsignedBigInteger('profileId');
            $table->foreign('profileId')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_privacy');
    }
}
