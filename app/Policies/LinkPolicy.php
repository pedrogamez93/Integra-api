<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\Link;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any links.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can view the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function view(User $user, Link $link)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create links.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can update the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function update(User $user, Link $link)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can delete the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function delete(User $user, Link $link)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can restore the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function restore(User $user, Link $link)
    {
        return $this->userHelper->validRol($user);

    }

    /**
     * Determine whether the user can permanently delete the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function forceDelete(User $user, Link $link)
    {
        return $this->userHelper->validRol($user);
    }
}
