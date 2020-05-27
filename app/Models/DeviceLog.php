<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    protected $fillable = [
        'device_id', 'nearby_device', 'latitude', 'longitude', 'speed', 'area', 'device_name'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function nearby()
    {
        return $this->belongsTo(Device::class, 'nearby_device', 'id');
    }
}
