<?php

namespace App\Observers;

use App\Enums\HealthStatus;
use App\Models\ChangeRequest;
use App\Models\Sequence;

class ChangeRequestObserver
{


    /**
     * Handle the change request "updated" event.
     *
     * @param ChangeRequest $changeRequest
     * @return void
     */
    public function updated(ChangeRequest $changeRequest)
    {
        if ($changeRequest->isDirty('status') && $changeRequest['status'] === 'approve') {
            $user = auth()->user();
            $localNumbering = null;
            $globalNumbering = null;

            if ($changeRequest['user_status'] !== HealthStatus::HEALTHY) {
                $localPrefix = $user['prefix'];
                $localNumbering = $localPrefix ? $localPrefix . '-' . Sequence::getNextNumber($localPrefix) : null;
                $globalPrefix = "sekitar";
                $globalNumbering = $globalPrefix . '-' . Sequence::getNextNumber($globalPrefix);
            }
            $changeRequest->device->update([
                'user_status' => $changeRequest['user_status'],
                'name' => $changeRequest['name'],
                'nik' => $changeRequest['nik'],
                'local_numbering' => $localNumbering,
                'global_numbering' => $globalNumbering
            ]);
        }
    }

}
