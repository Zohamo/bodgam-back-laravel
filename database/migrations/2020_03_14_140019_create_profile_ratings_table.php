<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_ratings', function (Blueprint $table) {
            $table->tinyInteger('value');

            // FK

            $table->unsignedBigInteger('profileId');
            $table->foreign('profileId')->references('id')->on('profiles')->onDelete('cascade');

            $table->unsignedBigInteger('voterId');
            $table->foreign('voterId')->references('id')->on('users');

            $table->unsignedTinyInteger('type')->nullable();
            $table->foreign('type')->references('id')->on('profile_ratings_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_ratings');
    }
}
