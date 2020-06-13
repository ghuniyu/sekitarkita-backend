<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UuidIndex
{
    protected static function bootUuidIndex()
    {
        static::creating(function (Model $model) {
            $uuid = Str::orderedUuid();

            $model[$model->getKeyName()] = $uuid->getHex();
        });
    }

    public function getIncrementing()
    {
        return false;
    }
}
