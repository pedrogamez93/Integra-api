<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\User;
use App\UserNova;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserNovaPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }
    /**
     * Determine whether the user can view any user novas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can view the user nova.
     *
     * @param  \App\User  $user
     * @param  \App\UserNova  $userNova
     * @return mixed
     */
    public function view(User $user, UserNova $userNova)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create user novas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can update the user nova.
     *
     * @param  \App\User  $user
     * @param  \App\UserNova  $userNova
     * @return mixed
     */
    public function update(User $user, UserNova $userNova)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can delete the user nova.
     *
     * @param  \App\User  $user
     * @param  \App\UserNova  $userNova
     * @return mixed
     */
    public function delete(User $user, UserNova $userNova)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can restore the user nova.
     *
     * @param  \App\User  $user
     * @param  \App\UserNova  $userNova
     * @return mixed
     */
    public function restore(User $user, UserNova $userNova)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can permanently delete the user nova.
     *
     * @param  \App\User  $user
     * @param  \App\UserNova  $userNova
     * @return mixed
     */
    public function forceDelete(User $user, UserNova $userNova)
    {
        return $this->userHelper->validRol($user);
    }
}
