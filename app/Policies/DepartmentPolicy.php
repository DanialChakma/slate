<?php

namespace App\Policies;

use App\User;
use App\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user,$department)
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
     * Determine whether the user can view the department.
     *
     * @param  \App\User  $user
     * @param  \App\Department  $department
     * @return mixed
     */
    public function view(User $user, Department $department)
    {
        //
    }

    /**
     * Determine whether the user can create departments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the department.
     *
     * @param  \App\User  $user
     * @param  \App\Department  $department
     * @return mixed
     */
    public function update(User $user, Department $department)
    {
        //
    }

    /**
     * Determine whether the user can delete the department.
     *
     * @param  \App\User  $user
     * @param  \App\Department  $department
     * @return mixed
     */
    public function delete(User $user, Department $department)
    {
        //
    }
}
