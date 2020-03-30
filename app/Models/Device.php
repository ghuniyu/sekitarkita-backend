<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Device extends Model
{
    protected $appends = ['online'];

    protected $fillable = ['id', 'firebase_token','label','phone','device_name','health_condition'];

    public $incrementing = false;

    function nearbies()
    {
        return $this->hasMany(Nearby::class);
    }

    public function scopeHealthy($query)
    {
        return $query->where('health_condition', 'healthy');
    }

    public function scopePdp($query)
    {
        return $query->where('health_condition', 'pdp');
    }

    public function scopeOdp($query)
    {
        return $query->where('health_condition', 'odp');
    }

    public function scopeOnline($query)
    {
        return $query->where('updated_at', '>', Carbon::now('Asia/Jakarta')->addMinutes(-30));
    }

    public function getOnlineAttribute()
    {
        return $this['updated_at'] > Carbon::now('Asia/Jakarta')->addMinutes(-30);
    }
}
