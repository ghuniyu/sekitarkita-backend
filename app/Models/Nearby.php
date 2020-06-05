<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Nearby extends Pivot
{
    public $incrementing = false;

    protected $fillable = [
        'device_id',
        'another_device',
        'device_name',
        'latitude',
        'longitude',
        'speed'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function scopeWithAppUser($query)
    {
        return $query->addSelect(['app_user' => Device::select('app_user')
            ->whereColumn('another_device', 'devices.id')
        ])->withCasts(['app_user' => 'boolean'])->addSelect(['user_status' => Device::select('user_status')
            ->whereColumn('another_device', 'devices.id')
        ]);
    }
}
