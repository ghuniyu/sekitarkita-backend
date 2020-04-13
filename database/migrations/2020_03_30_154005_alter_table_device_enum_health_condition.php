<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDeviceEnumHealthCondition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            DB::statement("ALTER TABLE devices CHANGE COLUMN health_condition health_condition ENUM('healthy', 'pdp', 'odp', 'confirmed','odr') NOT NULL DEFAULT 'healthy'");
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
            DB::statement("ALTER TABLE devices CHANGE COLUMN health_condition health_condition ENUM('healthy', 'pdp', 'odp', 'confirmed') NOT NULL DEFAULT 'healthy'");
        });
    }
}
