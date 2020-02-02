<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerOption extends Model
{
    protected $table = 'answer_options';
    protected $fillable = ['key', 'body','question_id'];
    public function question(){
        return $this->belongsTo('App\Question');
    }

    public function meetingSurveryResults(){
        return $this->hasMany('App\MeetingSurveryResult');
    }
}
