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
            $table->boolean('showBggName')->default(0);
            $table->boolean('showBirthdate')->default(0);
            $table->boolean('showEmail')->default(0);
            $table->boolean('showPhoneNumber')->default(0);
            $table->boolean('showWebsite')->default(0);

            // FK

            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
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
