<?php

use App\Enums\ChangeRequestStatus;
use App\Enums\SIKMCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSikmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sikm', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('device_id');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->string('nik');
            $table->string('name');
            $table->string('phone');
            $table->morphs('originable');
            $table->morphs('destinationable');
            $table->enum('category', SIKMCategory::getValues());
            $table->string('ktp_file');
            $table->string('medical_file');
            $table->date('medical_issued');
            $table->integer('person')->default(1);
            $table->enum('status', ChangeRequestStatus::getValues())->default(ChangeRequestStatus::PENDING);
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
        Schema::dropIfExists('sikm');
    }
}
