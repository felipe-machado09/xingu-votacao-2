<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LandingPageSection;
use Illuminate\Auth\Access\HandlesAuthorization;

class LandingPageSectionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LandingPageSection');
    }

    public function view(AuthUser $authUser, LandingPageSection $landingPageSection): bool
    {
        return $authUser->can('View:LandingPageSection');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LandingPageSection');
    }

    public function update(AuthUser $authUser, LandingPageSection $landingPageSection): bool
    {
        return $authUser->can('Update:LandingPageSection');
    }

    public function delete(AuthUser $authUser, LandingPageSection $landingPageSection): bool
    {
        return $authUser->can('Delete:LandingPageSection');
    }

    public function restore(AuthUser $authUser, LandingPageSection $landingPageSection): bool
    {
        return $authUser->can('Restore:LandingPageSection');
    }

    public function forceDelete(AuthUser $authUser, LandingPageSection $landingPageSection): bool
    {
        return $authUser->can('ForceDelete:LandingPageSection');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LandingPageSection');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LandingPageSection');
    }

    public function replicate(AuthUser $authUser, LandingPageSection $landingPageSection): bool
    {
        return $authUser->can('Replicate:LandingPageSection');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LandingPageSection');
    }

}