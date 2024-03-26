<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\Release;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReleasePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any releases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'release');
    }

    /**
     * Determine whether the user can view the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function view(User $user, Release $release)
    {
        return $this->userHelper->validRol($user, 'release');
    }

    /**
     * Determine whether the user can create releases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user, 'release');
    }

    /**
     * Determine whether the user can update the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function update(User $user, Release $release)
    {
        return $this->userHelper->validRol($user, 'release');
    }

    /**
     * Determine whether the user can delete the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function delete(User $user, Release $release)
    {
        return $this->userHelper->validRol($user, 'release');
    }

    /**
     * Determine whether the user can restore the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function restore(User $user, Release $release)
    {
        return $this->userHelper->validRol($user, 'release');
    }

    /**
     * Determine whether the user can permanently delete the release.
     *
     * @param  \App\User  $user
     * @param  \App\Release  $release
     * @return mixed
     */
    public function forceDelete(User $user, Release $release)
    {
        return $this->userHelper->validRol($user, 'release');
    }
}
