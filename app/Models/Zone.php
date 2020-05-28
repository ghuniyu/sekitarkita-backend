<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    public function area()
    {
        return $this->morphTo();
    }
}
