<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Models\Role;

class RoleAssignedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Role $role;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
}
