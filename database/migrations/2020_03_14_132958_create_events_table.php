<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string('title', 128);
            $table->boolean('isPrivate')->default(0);

            // Datetime

            $table->dateTime('startDatetime')->nullable();
            $table->dateTime('endDatetime')->nullable();

            // Location

            $table->unsignedBigInteger('locationId');
            $table->foreign('locationId')->references('id')->on('locations');

            // Host

            $table->unsignedBigInteger('userId');
            $table->foreign('userId')->references('id')->on('users');

            // Players

            $table->unsignedSmallInteger('minPlayers')->nullable();
            $table->unsignedSmallInteger('maxPlayers')->nullable();

            // Details

            $table->longText('description')->nullable();
            $table->unsignedTinyInteger('level')->nullable();
            $table->unsignedTinyInteger('atmosphere')->nullable();

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
        Schema::dropIfExists('events');
    }
}
