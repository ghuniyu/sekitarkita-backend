<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSelfChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('self_checks', function (Blueprint $table) {
            $table->string('age')->nullable()->after('phone');
            $table->string('address')->nullable()->after('age');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('self_checks', function (Blueprint $table) {
            $table->dropColumn('age');
            $table->dropColumn('address');
        });
    }
}
