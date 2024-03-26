<?php

namespace App\Policies;

use App\Category;
use App\Helpers\UtilHelper;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any educational materials.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can view the educational material.
     *
     * @param  \App\User  $user
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return mixed
     */
    public function view(User $user, Category $educationalMaterial)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can create educational materials.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can update the educational material.
     *
     * @param  \App\User  $user
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return mixed
     */
    public function update(User $user, Category $educationalMaterial)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can delete the educational material.
     *
     * @param  \App\User  $user
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return mixed
     */
    public function delete(User $user, Category $educationalMaterial)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can restore the educational material.
     *
     * @param  \App\User  $user
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return mixed
     */
    public function restore(User $user, Category $educationalMaterial)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }

    /**
     * Determine whether the user can permanently delete the educational material.
     *
     * @param  \App\User  $user
     * @param  \App\EducationalMaterial  $educationalMaterial
     * @return mixed
     */
    public function forceDelete(User $user, Category $educationalMaterial)
    {
        return $this->userHelper->validRol($user, 'materialEducation');
    }
}
