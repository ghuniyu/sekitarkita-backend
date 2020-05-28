<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'indonesia_cities';

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'province_id');
    }

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'city_id');
    }

    public function hospitals()
    {
        return $this->morphMany(Hospital::class, 'area');
    }

    public function call_centers()
    {
        return $this->morphMany(CallCenter::class, 'area');
    }
}
