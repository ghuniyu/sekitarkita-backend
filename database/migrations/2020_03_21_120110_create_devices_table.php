<?php

use App\Enums\HealthStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->string('nik',16)->nullable();
            $table->string('phone')->nullable();
            $table->string('global_numbering')->unique()->nullable();
            $table->string('local_numbering')->unique()->nullable();
            $table->enum('user_status', HealthStatus::getValues()); /* TODO : Add Mode Status*/
            $table->string('device_name')->nullable();
            $table->decimal('last_known_latitude', 10, 7)->nullable();
            $table->decimal('last_known_longitude', 10, 7)->nullable();
            $table->string('last_known_area')->nullable();
            $table->boolean('banned')->default(false);
            $table->string('firebase_token')->nullable();
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
        Schema::dropIfExists('devices');
    }
}
