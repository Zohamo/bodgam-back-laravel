<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_player', function (Blueprint $table) {
            $table->boolean('isAccepted')->default(0);
            $table->boolean('hasConfirmed')->default(0);

            // FK

            $table->unsignedBigInteger('eventId');
            $table->foreign('eventId')->references('id')->on('events');
            $table->unsignedBigInteger('userId');
            $table->foreign('userId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_player');
    }
}
