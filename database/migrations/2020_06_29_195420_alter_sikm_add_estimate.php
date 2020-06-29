<?php

use App\Enums\Transportation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSikmAddEstimate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sikm', function (Blueprint $table) {
            $table->dateTime('arrival_estimation')->nullable(); /* remove nullable after apps updated */
            $table->enum('transportation', Transportation::getValues())->nullable(); /* remove nullable after apps updated */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sikm', function (Blueprint $table) {
            //
        });
    }
}
