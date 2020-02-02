<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'name', 'description',
    ];

    public function users(){
        return $this->hasMany('App\User');
    }

    public function projects(){
        return $this->hasMany('App\Project');
    }

    public function surveys(){
        return $this->hasMany('App\Survey');
    }
}
