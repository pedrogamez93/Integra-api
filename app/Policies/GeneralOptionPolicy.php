<?php

namespace App\Policies;

use App\GeneralOption;
use App\Helpers\UtilHelper;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GeneralOptionPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any general options.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'generalOption');
    }

    /**
     * Determine whether the user can view the general option.
     *
     * @param  \App\User  $user
     * @param  \App\GeneralOption  $generalOption
     * @return mixed
     */
    public function view(User $user, GeneralOption $generalOption)
    {
        return $this->userHelper->validRol($user, 'generalOption');
    }

    /**
     * Determine whether the user can create general options.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the general option.
     *
     * @param  \App\User  $user
     * @param  \App\GeneralOption  $generalOption
     * @return mixed
     */
    public function update(User $user, GeneralOption $generalOption)
    {
        return $this->userHelper->validRol($user, 'generalOption');
    }

    /**
     * Determine whether the user can delete the general option.
     *
     * @param  \App\User  $user
     * @param  \App\GeneralOption  $generalOption
     * @return mixed
     */
    public function delete(User $user, GeneralOption $generalOption)
    {
        return $this->userHelper->validRol($user, 'generalOption');
    }

    /**
     * Determine whether the user can restore the general option.
     *
     * @param  \App\User  $user
     * @param  \App\GeneralOption  $generalOption
     * @return mixed
     */
    public function restore(User $user, GeneralOption $generalOption)
    {
        return $this->userHelper->validRol($user, 'generalOption');
    }

    /**
     * Determine whether the user can permanently delete the general option.
     *
     * @param  \App\User  $user
     * @param  \App\GeneralOption  $generalOption
     * @return mixed
     */
    public function forceDelete(User $user, GeneralOption $generalOption)
    {
        return $this->userHelper->validRol($user, 'generalOption');
    }
}
