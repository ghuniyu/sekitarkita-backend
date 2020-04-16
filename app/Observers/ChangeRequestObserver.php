<?php

namespace App\Observers;

use App\Models\ChangeRequest;

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
            $changeRequest->device->update([
                'health_condition' => $changeRequest['health_condition']
            ]);
        }
    }

}
