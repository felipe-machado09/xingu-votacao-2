<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Vote;
use Illuminate\Auth\Access\HandlesAuthorization;

class VotePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Vote');
    }

    public function view(AuthUser $authUser, Vote $vote): bool
    {
        return $authUser->can('View:Vote');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Vote');
    }

    public function update(AuthUser $authUser, Vote $vote): bool
    {
        return $authUser->can('Update:Vote');
    }

    public function delete(AuthUser $authUser, Vote $vote): bool
    {
        return $authUser->can('Delete:Vote');
    }

    public function restore(AuthUser $authUser, Vote $vote): bool
    {
        return $authUser->can('Restore:Vote');
    }

    public function forceDelete(AuthUser $authUser, Vote $vote): bool
    {
        return $authUser->can('ForceDelete:Vote');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Vote');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Vote');
    }

    public function replicate(AuthUser $authUser, Vote $vote): bool
    {
        return $authUser->can('Replicate:Vote');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Vote');
    }

}