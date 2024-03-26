<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\Termn;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermnPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any termns.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can view the termn.
     *
     * @param  \App\User  $user
     * @param  \App\Termn  $termn
     * @return mixed
     */
    public function view(User $user, Termn $termn)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create termns.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the termn.
     *
     * @param  \App\User  $user
     * @param  \App\Termn  $termn
     * @return mixed
     */
    public function update(User $user, Termn $termn)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can delete the termn.
     *
     * @param  \App\User  $user
     * @param  \App\Termn  $termn
     * @return mixed
     */
    public function delete(User $user, Termn $termn)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can restore the termn.
     *
     * @param  \App\User  $user
     * @param  \App\Termn  $termn
     * @return mixed
     */
    public function restore(User $user, Termn $termn)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the termn.
     *
     * @param  \App\User  $user
     * @param  \App\Termn  $termn
     * @return mixed
     */
    public function forceDelete(User $user, Termn $termn)
    {
        //
    }
}
