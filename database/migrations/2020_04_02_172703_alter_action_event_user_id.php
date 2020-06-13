<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterActionEventUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_events', function (Blueprint $table) {
            $table->string('user_id')->change();
            $table->string('actionable_id')->change();
            $table->string('target_id')->change();
            $table->string('model_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_events', function (Blueprint $table) {

        });
    }
}
