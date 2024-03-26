<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\User;
use App\UserContribution;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserContributionPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any user contributions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can view the user contribution.
     *
     * @param  \App\User  $user
     * @param  \App\UserContribution  $userContribution
     * @return mixed
     */
    public function view(User $user, UserContribution $userContribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can create user contributions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the user contribution.
     *
     * @param  \App\User  $user
     * @param  \App\UserContribution  $userContribution
     * @return mixed
     */
    public function update(User $user, UserContribution $userContribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can delete the user contribution.
     *
     * @param  \App\User  $user
     * @param  \App\UserContribution  $userContribution
     * @return mixed
     */
    public function delete(User $user, UserContribution $userContribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can restore the user contribution.
     *
     * @param  \App\User  $user
     * @param  \App\UserContribution  $userContribution
     * @return mixed
     */
    public function restore(User $user, UserContribution $userContribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can permanently delete the user contribution.
     *
     * @param  \App\User  $user
     * @param  \App\UserContribution  $userContribution
     * @return mixed
     */
    public function forceDelete(User $user, UserContribution $userContribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }
}
