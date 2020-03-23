<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['id', 'firebase_token'];

    public $incrementing = false;

    function nearbies(){
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
}
