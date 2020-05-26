<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfCheck extends Model
{
    protected $fillable = [
        'device_id', 'has_fever', 'has_flu', 'has_cough', 'has_breath_problem', 'has_sore_throat', 'has_in_infected_country', 'has_in_infected_city', 'has_direct_contact', 'result', 'phone', 'name'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
