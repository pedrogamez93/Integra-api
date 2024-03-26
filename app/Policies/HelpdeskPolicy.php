<?php

namespace App\Policies;

use App\Helpdesk;
use App\Helpers\UtilHelper;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HelpdeskPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any helpdesks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'helpdesk');
    }

    /**
     * Determine whether the user can view the helpdesk.
     *
     * @param  \App\User  $user
     * @param  \App\Helpdesk  $helpdesk
     * @return mixed
     */
    public function view(User $user, Helpdesk $helpdesk)
    {
        return $this->userHelper->validRol($user, 'helpdesk');
    }

    /**
     * Determine whether the user can create helpdesks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user, 'helpdesk');
    }

    /**
     * Determine whether the user can update the helpdesk.
     *
     * @param  \App\User  $user
     * @param  \App\Helpdesk  $helpdesk
     * @return mixed
     */
    public function update(User $user, Helpdesk $helpdesk)
    {
        return $this->userHelper->validRol($user, 'helpdesk');
    }

    /**
     * Determine whether the user can delete the helpdesk.
     *
     * @param  \App\User  $user
     * @param  \App\Helpdesk  $helpdesk
     * @return mixed
     */
    public function delete(User $user, Helpdesk $helpdesk)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can restore the helpdesk.
     *
     * @param  \App\User  $user
     * @param  \App\Helpdesk  $helpdesk
     * @return mixed
     */
    public function restore(User $user, Helpdesk $helpdesk)
    {
        return $this->userHelper->validRol($user, 'helpdesk');
    }

    /**
     * Determine whether the user can permanently delete the helpdesk.
     *
     * @param  \App\User  $user
     * @param  \App\Helpdesk  $helpdesk
     * @return mixed
     */
    public function forceDelete(User $user, Helpdesk $helpdesk)
    {
        return $this->userHelper->validRol($user, 'helpdesk');
    }
}
