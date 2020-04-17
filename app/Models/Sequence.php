<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    protected $fillable = [
        'name', 'number'
    ];

    public static function getNextNumber($name)
    {
        $sequence = static::query()->where('name', $name)->latest('number')->first();
        if (!$sequence) {
            $newSequence = Sequence::create(['name' => $name, 'number' => 1]);
            return $newSequence['number'];
        }

        return $sequence->increment('number');
    }
}
