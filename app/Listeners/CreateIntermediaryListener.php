<?php

namespace App\Listeners;

use App\Events\RoleAssignedEvent;
use App\Models\Intermediary;

class CreateIntermediaryListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RoleAssignedEvent $event): void
    {
        if ($event->role->name === 'Intermediary') {
            try {
                Intermediary::create([
                    'intermediary_id' => $event->user->id,
                ]);
            } catch (\Throwable $e) {
                logger('Intermediary not saved: ' . $e->getMessage());
            }
        }
    }
}
