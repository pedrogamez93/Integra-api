<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\Survey;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any Surveys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can view the Survey.
     *
     * @param  \App\User  $user
     * @param  \App\Survey  $Survey
     * @return mixed
     */
    public function view(User $user, Survey $survey)
    {
        return $this->userHelper->validRol($user);
    }

    /**
     * Determine whether the user can create Surveys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can update the Survey.
     *
     * @param  \App\User  $user
     * @param  \App\Survey  $Survey
     * @return mixed
     */
    public function update(User $user, Survey $survey)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can delete the Survey.
     *
     * @param  \App\User  $user
     * @param  \App\Survey  $Survey
     * @return mixed
     */
    public function delete(User $user, Survey $survey)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can restore the region.
     *
     * @param  \App\User  $user
     * @param  \App\Region  $region
     * @return mixed
     */
    public function restore(User $user, Survey $survey)
    {
        return $this->userHelper->validRol($user,'survey');
    }

    /**
     * Determine whether the user can permanently delete the region.
     *
     * @param  \App\User  $user
     * @param  \App\Region  $region
     * @return mixed
     */
    public function forceDelete(User $user, Survey $survey)
    {
        return $this->userHelper->validRol($user,'survey');
    }
}
