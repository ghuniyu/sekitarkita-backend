<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterDeviceUpdateEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            DB::statement("ALTER TABLE devices CHANGE COLUMN health_condition health_condition ENUM('healthy', 'pdp', 'odp', 'confirmed') NOT NULL DEFAULT 'healthy'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            DB::statement("ALTER TABLE devices CHANGE COLUMN health_condition health_condition ENUM('healthy', 'pdp', 'odp') NOT NULL DEFAULT 'healthy'");
        });
    }
}
