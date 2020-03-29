<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallCenter extends Model
{
    public function area()
    {
        return $this->morphTo();
    }
}
