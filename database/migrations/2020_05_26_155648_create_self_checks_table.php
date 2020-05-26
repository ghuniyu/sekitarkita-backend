<?php

use App\Enums\HealthStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelfChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('self_checks', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('has_direct_contact');
            $table->boolean('has_fever');
            $table->boolean('has_flu');
            $table->boolean('has_cough');
            $table->boolean('has_breath_problem');
            $table->boolean('has_sore_throat');
            $table->boolean('has_in_infected_country');
            $table->boolean('has_in_infected_city');
            $table->enum('result', HealthStatus::getValues());
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
        Schema::dropIfExists('self_checks');
    }
}
