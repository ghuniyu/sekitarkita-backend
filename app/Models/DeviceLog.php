<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    protected $fillable = [
        'device_id', 'nearby_device', 'latitude', 'longitude', 'speed', 'area', 'device_name'
    ];

    protected $appends = ['another_device'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function nearby()
    {
        return $this->belongsTo(Device::class, 'nearby_device', 'id');
    }

    public function getAnotherDeviceAttribute()
    {
        return $this->attributes['nearby_device'];
    }

    public function scopeWithAppUser($query)
    {
        return $query->addSelect(['app_user' => Device::select('app_user')
            ->whereColumn('nearby_device', 'devices.id')
        ])->withCasts(['app_user' => 'boolean']);
    }
}
