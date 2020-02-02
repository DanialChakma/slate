<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param $userToBeAccessed
     * @return bool|null
     */
    public function before(User $user, $userToBeAccessed)
    {
        if($user->isAdmin()) return true;
        else return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewList(User $user)
    {

    }

    /**
     * @param User $user
     * @param User $userToBeAccessed
     * @return bool
     */
    public function view(User $user, User $userToBeAccessed)
    {
        return $user->id == $userToBeAccessed->id;
    }

    /**
     * @param User $user
     */
    public function create(User $user)
    {
        //
    }

    /**
     * @param User $user
     * @param User $userToBeAccessed
     * @return bool
     */
    public function update(User $user, User $userToBeAccessed)
    {
        return $user->id == $userToBeAccessed->id;
    }

    /**
     * @param User $user
     * @param User $userToBeAccessed
     * @return bool
     */
    public function passwordUpdateForAdmin(User $user, User $userToBeAccessed)
    {

    }

    /**
     * @param User $user
     * @param User $userToBeAccessed
     */
    public function delete(User $user, User $userToBeAccessed)
    {
        //
    }
}
