<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_subscriptions', function (Blueprint $table) {
            $table->boolean('isAccepted')->nullable();
            $table->boolean('hasConfirmed')->default(0);

            // FK

            $table->unsignedBigInteger('eventId');
            $table->foreign('eventId')->references('id')->on('events');
            $table->unsignedBigInteger('userId');
            $table->foreign('userId')->references('id')->on('users');

            // Timestamps

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
        Schema::dropIfExists('event_subscriptions');
    }
}
