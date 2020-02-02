<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = ['body', 'type'];

    public function answerOptions(){
        return $this->hasMany('App\AnswerOption');
    }

    public function meetingSurveryResults(){
        return $this->hasMany('App\MeetingSurveryResult');
    }

    public function surveys() {
        return $this->belongsToMany('App\Survey', 'question_survey', 'question_id','survey_id');
    }
}
