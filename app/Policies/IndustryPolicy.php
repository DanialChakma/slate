<?php

namespace App\Policies;

use App\User;
use App\Industry;
use Illuminate\Auth\Access\HandlesAuthorization;

class IndustryPolicy
{
    use HandlesAuthorization;

    public function before(User $user,$industry)
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
     * Determine whether the user can view the Industry.
     *
     * @param  \App\User  $user
     * @param  \App\Industry  $industry
     * @return mixed
     */
    public function view(User $user, Industry $industry)
    {
        //
    }

    /**
     * Determine whether the user can create =Industries.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the Industry.
     *
     * @param  \App\User  $user
     * @param  \App\Industry  $industry
     * @return mixed
     */
    public function update(User $user, Industry $industry)
    {
        //
    }

    /**
     * Determine whether the user can delete the Industry.
     *
     * @param  \App\User  $user
     * @param  \App\Industry  $industry
     * @return mixed
     */
    public function delete(User $user, Industry $industry)
    {
        //
    }
}
