<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\User;
use App\welcomePage;
use Illuminate\Auth\Access\HandlesAuthorization;

class welcomePagePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any welcome pages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can view the welcome page.
     *
     * @param  \App\User  $user
     * @param  \App\welcomePage  $welcomePage
     * @return mixed
     */
    public function view(User $user, welcomePage $welcomePage)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create welcome pages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the welcome page.
     *
     * @param  \App\User  $user
     * @param  \App\welcomePage  $welcomePage
     * @return mixed
     */
    public function update(User $user, welcomePage $welcomePage)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can delete the welcome page.
     *
     * @param  \App\User  $user
     * @param  \App\welcomePage  $welcomePage
     * @return mixed
     */
    public function delete(User $user, welcomePage $welcomePage)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can restore the welcome page.
     *
     * @param  \App\User  $user
     * @param  \App\welcomePage  $welcomePage
     * @return mixed
     */
    public function restore(User $user, welcomePage $welcomePage)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can permanently delete the welcome page.
     *
     * @param  \App\User  $user
     * @param  \App\welcomePage  $welcomePage
     * @return mixed
     */
    public function forceDelete(User $user, welcomePage $welcomePage)
    {
        return $this->userHelper->validRol($user);
    }
}
