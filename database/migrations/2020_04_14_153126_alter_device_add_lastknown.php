<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeviceAddLastKnown extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->decimal('last_known_latitude', 10, 7)->nullable()->after('health_condition');
            $table->decimal('last_known_longitude', 10, 7)->nullable()->after('last_known_latitude');
            $table->decimal('last_known_area', 10, 7)->nullable()->after('last_known_longitude');
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
            $table->dropColumn('last_known_latitude');
            $table->dropColumn('last_known_longitude');
            $table->dropColumn('last_known_area');
        });
    }
}
