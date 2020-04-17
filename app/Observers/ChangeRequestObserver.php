<?php

namespace App\Observers;

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

            if ($changeRequest['health_condition'] !== 'healthy') {
                $localPrefix = $user['prefix'];
                $localNumbering = $localPrefix . '-' . Sequence::getNextNumber($localPrefix);
                $globalPrefix = "sekitar";
                $globalNumbering = $globalPrefix . '-' . Sequence::getNextNumber($globalPrefix);
            }
            $changeRequest->device->update([
                'health_condition' => $changeRequest['health_condition'],
                'name' => $changeRequest['name'],
                'nik' => $changeRequest['nik'],
                'local_numbering' => $localNumbering,
                'global_numbering' => $globalNumbering
            ]);
        }
    }

}
