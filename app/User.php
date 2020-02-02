<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'name', 'email', 'password', 'role_id', 'supervisor_id', 'department_id', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the  role of the User
     */
    public function role(){
        return $this->belongsTo('App\Role');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(){
        return $this->belongsTo('App\Department');
    }

//    public function meetings(){
//        return $this->hasMany('App\Meeting');
//    }

    public function meetings(){
        return $this->belongsToMany('App\Meeting', 'meeting_user', 'user_id','meeting_id');
    }

     public function supervisor(){
        return $this->belongsTo('App\User');
    }

    public function employeesUnderHim(){
        return $this->hasMany('App\User', 'supervisor_id',  'id');
    }

    public function isAdmin(){
        return $this->role_id == 1;
    }

    public function isManager(){
        return $this->role_id == 2;
    }

    public function isSupervisor(){
        return $this->role_id == 3;
    }

    public function isFieldStuff(){
        return $this->role_id == 4;
    }
}
