<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
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
