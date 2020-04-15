<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    protected $fillable = ['device_id', 'health_condition', 'status', 'nik', 'name', 'phone'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
