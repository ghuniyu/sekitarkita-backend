<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDeviceAddProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('name')->after('last_known_area')->nullable();
            $table->string('nik',16)->after('name')->nullable();
            $table->string('global_numbering')->after('nik')->unique()->nullable();
            $table->string('local_numbering')->after('global_numbering')->unique()->nullable();
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
            $table->dropColumn('name');
            $table->dropColumn('nik');
        });
    }
}
