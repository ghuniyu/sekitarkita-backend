<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'indonesia_districts';

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'city_id');
    }

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'district_id');
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
