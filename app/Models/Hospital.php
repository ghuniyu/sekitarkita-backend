<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    public function area()
    {
        return $this->morphTo();
    }
}
