<?php

namespace App\Policies;

use App\User;
use App\ClientCompany;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientCompanyPolicy
{
    use HandlesAuthorization;

    public function before(User $user,$clientCompany)
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
     * Determine whether the user can view the ClientCompany.
     *
     * @param  \App\User  $user
     * @param  \App\ClientCompany  $clientCompany
     * @return mixed
     */
    public function view(User $user, ClientCompany $clientCompany)
    {
        //
    }

    /**
     * Determine whether the user can create =ClientCompanies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the ClientCompany.
     *
     * @param  \App\User  $user
     * @param  \App\ClientCompany  $clientCompany
     * @return mixed
     */
    public function update(User $user, ClientCompany $clientCompany)
    {
        //
    }

    /**
     * Determine whether the user can delete the ClientCompany.
     *
     * @param  \App\User  $user
     * @param  \App\ClientCompany  $clientCompany
     * @return mixed
     */
    public function delete(User $user, ClientCompany $clientCompany)
    {
        //
    }
}
