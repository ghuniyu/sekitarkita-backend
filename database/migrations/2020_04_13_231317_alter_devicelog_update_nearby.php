<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDevicelogUpdateNearby extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_logs', function (Blueprint $table) {
            $table->string('nearby_device')->nullable()->change();
            $table->string('area')->nullable();
            $table->double('speed')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_logs', function (Blueprint $table) {
            $table->string('nearby_device')->change();
            $table->dropColumn('area');
            $table->dropColumn('speed');
        });
    }
}
