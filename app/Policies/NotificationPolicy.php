<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\Notification;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can view the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function view(User $user, Notification $notification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function update(User $user, Notification $notification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can delete the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function delete(User $user, Notification $notification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can restore the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function restore(User $user, Notification $notification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can permanently delete the notification.
     *
     * @param  \App\User  $user
     * @param  \App\Notification  $notification
     * @return mixed
     */
    public function forceDelete(User $user, Notification $notification)
    {
        return $this->userHelper->validRol($user);
    }
}
