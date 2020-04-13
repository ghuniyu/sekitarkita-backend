<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    protected $fillable = [
        'device_id', 'nearby_device', 'latitude', 'longitude', 'speed', 'area'
    ];
}
