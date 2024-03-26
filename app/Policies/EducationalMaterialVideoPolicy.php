<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\User;
use App\VideoEducational;
use Illuminate\Auth\Access\HandlesAuthorization;

class EducationalMaterialVideoPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any video educationals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can view the video educational.
     *
     * @param  \App\User  $user
     * @param  \App\VideoEducational  $videoEducational
     * @return mixed
     */
    public function view(User $user, VideoEducational $videoEducational)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can create video educationals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can update the video educational.
     *
     * @param  \App\User  $user
     * @param  \App\VideoEducational  $videoEducational
     * @return mixed
     */
    public function update(User $user, VideoEducational $videoEducational)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can delete the video educational.
     *
     * @param  \App\User  $user
     * @param  \App\VideoEducational  $videoEducational
     * @return mixed
     */
    public function delete(User $user, VideoEducational $videoEducational)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can restore the video educational.
     *
     * @param  \App\User  $user
     * @param  \App\VideoEducational  $videoEducational
     * @return mixed
     */
    public function restore(User $user, VideoEducational $videoEducational)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can permanently delete the video educational.
     *
     * @param  \App\User  $user
     * @param  \App\VideoEducational  $videoEducational
     * @return mixed
     */
    public function forceDelete(User $user, VideoEducational $videoEducational)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }
}
