<?php

namespace App\Policies;

use App\User;
use App\Meeting;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization;

    public function before(User $user,$meeting)
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
        return true;
    }

    /**
     * Determine whether the user can view the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function view(User $user, Meeting $meeting)
    {
        return true;
    }

    /**
     * Determine whether the user can create meetings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function update(User $user, Meeting $meeting)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the meeting.
     *
     * @param  \App\User  $user
     * @param  \App\Meeting  $meeting
     * @return mixed
     */
    public function delete(User $user, Meeting $meeting)
    {
        return false;
    }
}
