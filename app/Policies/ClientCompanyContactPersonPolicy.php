<?php

namespace App\Policies;

use App\User;
use App\ClientCompanyContactPerson;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientCompanyContactPersonPolicy
{
    use HandlesAuthorization;

    public function before(User $user,$clientCompanyContactPerson)
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
     * Determine whether the user can view the clientCompanyContactPerson.
     *
     * @param  \App\User  $user
     * @param  \App\ClientCompanyContactPerson  $clientCompanyContactPerson
     * @return mixed
     */
    public function view(User $user, ClientCompanyContactPerson $clientCompanyContactPerson)
    {
        //
    }

    /**
     * Determine whether the user can create clientCompanyContactPeople.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the clientCompanyContactPerson.
     *
     * @param  \App\User  $user
     * @param  \App\ClientCompanyContactPerson  $clientCompanyContactPerson
     * @return mixed
     */
    public function update(User $user, ClientCompanyContactPerson $clientCompanyContactPerson)
    {
        //
    }

    /**
     * Determine whether the user can delete the clientCompanyContactPerson.
     *
     * @param  \App\User  $user
     * @param  \App\ClientCompanyContactPerson  $clientCompanyContactPerson
     * @return mixed
     */
    public function delete(User $user, ClientCompanyContactPerson $clientCompanyContactPerson)
    {
        //
    }
}
