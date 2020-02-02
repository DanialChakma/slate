<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    protected $fillable = [
        'name', 'description', 'department_id'
    ];

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function meetings(){
        return $this->hasMany('App\Meeting');
    }

    public function surveys(){
        return $this->hasMany('App\Survey');
    }
}
