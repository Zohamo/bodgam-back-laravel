<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->string('name', 128);
            $table->boolean('isDefault')->default(0);
            $table->boolean('isPublic')->default(0);

            // Address

            $table->string('address1', 64)->nullable();
            $table->string('address2', 32)->nullable();
            $table->string('zipCode', 8)->nullable();
            $table->string('district', 64)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('country', 3);

            // Coordinates

            $table->double('latitude', 10, 7)->nullable();
            $table->double('longitude', 11, 7)->nullable();
            $table->unsignedSmallInteger('accuracy')->nullable();

            // Details

            $table->longText('description')->nullable();
            $table->boolean('isAllowedSmoking')->default(0);
            $table->boolean('isAccessible')->default(0);

            // FK

            $table->foreignId('user_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
