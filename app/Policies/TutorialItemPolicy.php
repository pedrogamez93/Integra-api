<?php

namespace App\Policies;

use App\Helpers\UtilHelper;
use App\TutorialItem;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TutorialItemPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->userHelper = new UtilHelper();
    }

    /**
     * Determine whether the user can view any tutorial items.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->userHelper->validRol($user, 'tutorial');
    }

    /**
     * Determine whether the user can view the tutorial item.
     *
     * @param  \App\User  $user
     * @param  \App\TutorialItem  $tutorialItem
     * @return mixed
     */
    public function view(User $user, TutorialItem $tutorialItem)
    {
        return $this->userHelper->validRol($user, 'tutorial');
    }

    /**
     * Determine whether the user can create tutorial items.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userHelper->validRol($user, 'tutorial');
    }

    /**
     * Determine whether the user can update the tutorial item.
     *
     * @param  \App\User  $user
     * @param  \App\TutorialItem  $tutorialItem
     * @return mixed
     */
    public function update(User $user, TutorialItem $tutorialItem)
    {
        return $this->userHelper->validRol($user, 'tutorial');
    }

    /**
     * Determine whether the user can delete the tutorial item.
     *
     * @param  \App\User  $user
     * @param  \App\TutorialItem  $tutorialItem
     * @return mixed
     */
    public function delete(User $user, TutorialItem $tutorialItem)
    {
        return $this->userHelper->validRol($user, 'tutorial');
    }

    /**
     * Determine whether the user can restore the tutorial item.
     *
     * @param  \App\User  $user
     * @param  \App\TutorialItem  $tutorialItem
     * @return mixed
     */
    public function restore(User $user, TutorialItem $tutorialItem)
    {
        return $this->userHelper->validRol($user, 'tutorial');
    }

    /**
     * Determine whether the user can permanently delete the tutorial item.
     *
     * @param  \App\User  $user
     * @param  \App\TutorialItem  $tutorialItem
     * @return mixed
     */
    public function forceDelete(User $user, TutorialItem $tutorialItem)
    {
        return $this->userHelper->validRol($user, 'tutorial');
    }
}
