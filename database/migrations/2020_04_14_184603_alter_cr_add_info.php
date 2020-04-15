<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCrAddInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('health_condition');
            $table->string('name')->nullable()->after('nik');
            $table->string('phone')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropColumn('nik');
            $table->dropColumn('name');
            $table->dropColumn('phone');
        });
    }
}
