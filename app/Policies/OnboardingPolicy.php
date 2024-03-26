<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\Onboarding;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OnboardingPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any onboardings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'onboarding');
    }

    /**
     * Determine whether the user can view the onboarding.
     *
     * @param  \App\User  $user
     * @param  \App\Onboarding  $onboarding
     * @return mixed
     */
    public function view(User $user, Onboarding $onboarding)
    {
        return $this->userHelper->validRol($user, 'onboarding');
    }

    /**
     * Determine whether the user can create onboardings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user, 'onboarding');
    }

    /**
     * Determine whether the user can update the onboarding.
     *
     * @param  \App\User  $user
     * @param  \App\Onboarding  $onboarding
     * @return mixed
     */
    public function update(User $user, Onboarding $onboarding)
    {
        return $this->userHelper->validRol($user, 'onboarding');
    }

    /**
     * Determine whether the user can delete the onboarding.
     *
     * @param  \App\User  $user
     * @param  \App\Onboarding  $onboarding
     * @return mixed
     */
    public function delete(User $user, Onboarding $onboarding)
    {
        return $this->userHelper->validRol($user, 'onboarding');
    }

    /**
     * Determine whether the user can restore the onboarding.
     *
     * @param  \App\User  $user
     * @param  \App\Onboarding  $onboarding
     * @return mixed
     */
    public function restore(User $user, Onboarding $onboarding)
    {
        return $this->userHelper->validRol($user, 'onboarding');
    }

    /**
     * Determine whether the user can permanently delete the onboarding.
     *
     * @param  \App\User  $user
     * @param  \App\Onboarding  $onboarding
     * @return mixed
     */
    public function forceDelete(User $user, Onboarding $onboarding)
    {
        return $this->userHelper->validRol($user, 'onboarding');
    }
}
