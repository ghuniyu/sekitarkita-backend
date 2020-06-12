<?php

namespace App\Models;

use App\Traits\UuidIndex;
use Illuminate\Database\Eloquent\Model;

class SIKM extends Model
{

    use UuidIndex;

    protected $table = 'sikm';

    protected $casts = [
        'medical_issued' => 'date'
    ];

    public function originable()
    {
        return $this->morphTo();
    }

    public function destinationable()
    {
        return $this->morphTo();
    }
}
