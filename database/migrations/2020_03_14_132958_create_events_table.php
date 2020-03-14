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
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->string('title', 128);
            $table->boolean('isPrivate')->default(0);

            // Datetime

            $table->timestamp('start_datetime')->nullable();
            $table->timestamp('end_datetime')->nullable();

            // Location

            $table->foreignId('location_id')->constrained();

            // Host

            $table->foreignId('user_id')->constrained();

            // Players

            $table->unsignedSmallInteger('minPlayers')->nullable();
            $table->unsignedSmallInteger('maxPlayers')->nullable();

            // Details

            $table->longText('description')->nullable();
            $table->unsignedTinyInteger('level_id')->nullable();
            $table->foreign('level_id')->references('id')->on('event_levels_types');
            $table->unsignedTinyInteger('atmosphere_id')->nullable();
            $table->foreign('atmosphere_id')->references('id')->on('event_atmospheres_types');
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
