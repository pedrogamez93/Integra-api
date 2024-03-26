<?php

namespace App\Policies;

use App\Constribution;
use App\Helpers\UtilHelper;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConstributionPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }
    /**
     * Determine whether the user can view any constributions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can view the constribution.
     *
     * @param  \App\User  $user
     * @param  \App\Constribution  $constribution
     * @return mixed
     */
    public function view(User $user, Constribution $constribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can create constributions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can update the constribution.
     *
     * @param  \App\User  $user
     * @param  \App\Constribution  $constribution
     * @return mixed
     */
    public function update(User $user, Constribution $constribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can delete the constribution.
     *
     * @param  \App\User  $user
     * @param  \App\Constribution  $constribution
     * @return mixed
     */
    public function delete(User $user, Constribution $constribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can restore the constribution.
     *
     * @param  \App\User  $user
     * @param  \App\Constribution  $constribution
     * @return mixed
     */
    public function restore(User $user, Constribution $constribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }

    /**
     * Determine whether the user can permanently delete the constribution.
     *
     * @param  \App\User  $user
     * @param  \App\Constribution  $constribution
     * @return mixed
     */
    public function forceDelete(User $user, Constribution $constribution)
    {
        return $this->userHelper->validRol($user, 'constribution');
    }
}
