<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';
    protected $fillable = ['name', 'remarks','project_id', 'department_id'];

    public function department(){
        return $this->belongsTo('App\Department','department_id','id');
    }

    public function project(){
        return $this->belongsTo('App\Project','project_id','id');
    }

    public function meetings(){
        return $this->hasMany('App\Meeting');
    }

    public function meetingSurveryResults(){
        return $this->hasMany('App\MeetingSurveryResult');
    }

    public function questions() {
        return $this->belongsToMany('App\Question', 'question_survey', 'survey_id','question_id');
    }
}
