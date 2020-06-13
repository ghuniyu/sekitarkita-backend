<?php

namespace App\Models;

use App\Enums\ChangeRequestStatus;
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

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function approve()
    {
        $this['status'] = ChangeRequestStatus::APPROVE;
        $this->save();
    }

    public function pending()
    {
        $this['status'] = ChangeRequestStatus::PENDING;
        $this->save();
    }

    public function reject()
    {
        $this['status'] = ChangeRequestStatus::REJECT;
        $this->save();
    }
}
