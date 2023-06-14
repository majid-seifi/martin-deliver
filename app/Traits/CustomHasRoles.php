<?php

namespace App\Traits;

use App\Events\RoleAssignedEvent;
use Spatie\Permission\Traits\HasRoles;

trait CustomHasRoles
{
    use HasRoles {
        assignRole as protected originalAssignRole;
    }

    /**
     * @param mixed ...$roles
     * @return $this
     */
    public function assignRole(...$roles)
    {
        $this->originalAssignRole(...$roles);

        $this->fireRoleAssignedEvent($roles);

        return $this;
    }

    /**
     * @param $role
     * @return bool
     */
    public function fireRoleAssignedEvent($role)
    {
        if (is_iterable($role)) {
            return array_walk($role, [$this, 'fireRoleAssignedEvent']);
        }
        event(new RoleAssignedEvent($this, $this->getStoredRole($role)));
        return true;
    }
}
