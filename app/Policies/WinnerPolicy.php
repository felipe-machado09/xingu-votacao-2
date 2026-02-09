<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Winner;
use Illuminate\Auth\Access\HandlesAuthorization;

class WinnerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Winner');
    }

    public function view(AuthUser $authUser, Winner $winner): bool
    {
        return $authUser->can('View:Winner');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Winner');
    }

    public function update(AuthUser $authUser, Winner $winner): bool
    {
        return $authUser->can('Update:Winner');
    }

    public function delete(AuthUser $authUser, Winner $winner): bool
    {
        return $authUser->can('Delete:Winner');
    }

    public function restore(AuthUser $authUser, Winner $winner): bool
    {
        return $authUser->can('Restore:Winner');
    }

    public function forceDelete(AuthUser $authUser, Winner $winner): bool
    {
        return $authUser->can('ForceDelete:Winner');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Winner');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Winner');
    }

    public function replicate(AuthUser $authUser, Winner $winner): bool
    {
        return $authUser->can('Replicate:Winner');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Winner');
    }

}