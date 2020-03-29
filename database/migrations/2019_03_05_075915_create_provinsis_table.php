<?php

use App\Models\Provinsi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\Csv\Reader;

class CreateProvinsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinsis', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->string('nama');
            $table->timestamps();
        });

        Provinsi::insert(collect(Reader::createFromPath(__DIR__ . '/../sources/provinsi.csv')->getRecords())->map(function (array $item) {
            return [
                'id' => $item[0],
                'nama' => $item[1]
            ];
        })->toArray());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provinsis');
    }
}
