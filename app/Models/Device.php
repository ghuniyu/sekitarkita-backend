<?php

namespace App\Models;

use App\Enums\HealthStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $appends = ['online'];

    protected $fillable = ['id', 'firebase_token', 'label', 'phone', 'device_name',
        'user_status', 'name', 'nik', 'local_numbering', 'global_numbering', 'banned', 'app_user'];

    public $incrementing = false;

    function nearbies()
    {
        return $this->hasMany(Nearby::class);
    }

    public function scopeHealthy($query)
    {
        return $query->where('user_status', HealthStatus::HEALTHY);
    }

    public function scopePdp($query)
    {
        return $query->where('user_status', HealthStatus::PDP);
    }

    public function scopeOdp($query)
    {
        return $query->where('user_status', HealthStatus::ODP);
    }

    public function scopeOnline($query)
    {
        return $query->where('updated_at', '>', Carbon::now('Asia/Jakarta')->addMinutes(-60));
    }

    public function getOnlineAttribute()
    {
        return $this['updated_at'] > Carbon::now('Asia/Jakarta')->addMinutes(-60);
    }

    public function selfChecks()
    {
        return $this->hasMany(SelfCheck::class);
    }
}
