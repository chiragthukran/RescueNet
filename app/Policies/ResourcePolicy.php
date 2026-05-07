<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    public function update(User $user, Resource $resource): bool
    {
        return $user->id === $resource->user_id;
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $user->id === $resource->user_id;
    }
}
