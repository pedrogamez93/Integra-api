<?php

namespace App\Policies;

use App\User;
use App\UserRutList;
use App\Helpers\UtilHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserRutListPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any user rut lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can view the user rut list.
     *
     * @param  \App\User  $user
     * @param  \App\UserRutList  $userRutList
     * @return mixed
     */
    public function view(User $user, UserRutList $userRutList)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create user rut lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can update the user rut list.
     *
     * @param  \App\User  $user
     * @param  \App\UserRutList  $userRutList
     * @return mixed
     */
    public function update(User $user, UserRutList $userRutList)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can delete the user rut list.
     *
     * @param  \App\User  $user
     * @param  \App\UserRutList  $userRutList
     * @return mixed
     */
    public function delete(User $user, UserRutList $userRutList)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can restore the user rut list.
     *
     * @param  \App\User  $user
     * @param  \App\UserRutList  $userRutList
     * @return mixed
     */
    public function restore(User $user, UserRutList $userRutList)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can permanently delete the user rut list.
     *
     * @param  \App\User  $user
     * @param  \App\UserRutList  $userRutList
     * @return mixed
     */
    public function forceDelete(User $user, UserRutList $userRutList)
    {
        return $this->userHelper->validRol($user,'survey');
    }
}