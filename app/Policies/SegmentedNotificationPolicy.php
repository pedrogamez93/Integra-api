<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\SegmentedNotification;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SegmentedNotificationPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any segmented notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can view the segmented notification.
     *
     * @param  \App\User  $user
     * @param  \App\SegmentedNotification  $segmentedNotification
     * @return mixed
     */
    public function view(User $user, SegmentedNotification $segmentedNotification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create segmented notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the segmented notification.
     *
     * @param  \App\User  $user
     * @param  \App\SegmentedNotification  $segmentedNotification
     * @return mixed
     */
    public function update(User $user, SegmentedNotification $segmentedNotification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can delete the segmented notification.
     *
     * @param  \App\User  $user
     * @param  \App\SegmentedNotification  $segmentedNotification
     * @return mixed
     */
    public function delete(User $user, SegmentedNotification $segmentedNotification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can restore the segmented notification.
     *
     * @param  \App\User  $user
     * @param  \App\SegmentedNotification  $segmentedNotification
     * @return mixed
     */
    public function restore(User $user, SegmentedNotification $segmentedNotification)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can permanently delete the segmented notification.
     *
     * @param  \App\User  $user
     * @param  \App\SegmentedNotification  $segmentedNotification
     * @return mixed
     */
    public function forceDelete(User $user, SegmentedNotification $segmentedNotification)
    {
        return $this->userHelper->validRol($user);
    }
}
