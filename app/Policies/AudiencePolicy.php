<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Audience;
use Illuminate\Auth\Access\HandlesAuthorization;

class AudiencePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Audience');
    }

    public function view(AuthUser $authUser, Audience $audience): bool
    {
        return $authUser->can('View:Audience');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Audience');
    }

    public function update(AuthUser $authUser, Audience $audience): bool
    {
        return $authUser->can('Update:Audience');
    }

    public function delete(AuthUser $authUser, Audience $audience): bool
    {
        return $authUser->can('Delete:Audience');
    }

    public function restore(AuthUser $authUser, Audience $audience): bool
    {
        return $authUser->can('Restore:Audience');
    }

    public function forceDelete(AuthUser $authUser, Audience $audience): bool
    {
        return $authUser->can('ForceDelete:Audience');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Audience');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Audience');
    }

    public function replicate(AuthUser $authUser, Audience $audience): bool
    {
        return $authUser->can('Replicate:Audience');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Audience');
    }

}