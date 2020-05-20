<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNearbyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nearby', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->string('device_name')->nullable();
            $table->string('another_device');
            $table->unique(['device_id', 'another_device']);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->double('speed')->nullable();
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
        Schema::dropIfExists('nearby');
    }
}
