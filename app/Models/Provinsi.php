<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class);
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
