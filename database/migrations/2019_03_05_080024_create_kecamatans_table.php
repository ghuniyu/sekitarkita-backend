<?php

use App\Models\Kecamatan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\Csv\Reader;

class CreateKecamatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->char('id', 7)->primary();
            $table->char('kabupaten_id', 4);
            $table->foreign('kabupaten_id')->on('kabupatens')->references('id');
            $table->string('nama');
            $table->timestamps();
        });

        Kecamatan::insert(collect(Reader::createFromPath(__DIR__ . '/../sources/kecamatan.csv')->getRecords())->map(function (array $item) {
            return [
                'id' => $item[0],
                'kabupaten_id' => $item[1],
                'nama' => $item[2]
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
        Schema::dropIfExists('kecamatans');
    }
}
