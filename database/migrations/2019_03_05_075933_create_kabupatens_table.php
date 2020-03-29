<?php

use App\Models\Kabupaten;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\Csv\Reader;

class CreateKabupatensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->char('id', 4)->primary();
            $table->char('provinsi_id', 2);
            $table->foreign('provinsi_id')->on('provinsis')->references('id');
            $table->string('nama');
            $table->timestamps();
        });

        Kabupaten::insert(collect(Reader::createFromPath(__DIR__ . '/../sources/kabupaten.csv')->getRecords())->map(function (array $item) {
            return [
                'id' => $item[0],
                'nama' => $item[2],
                'provinsi_id' => $item[1]
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
        Schema::dropIfExists('kabupatens');
    }
}
