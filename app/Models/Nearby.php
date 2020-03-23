<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Nearby extends Pivot
{
    public $incrementing = false;

    protected $fillable = [
        'device_id',
        'another_device',
        'latitude',
        'longitude'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
