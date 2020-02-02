<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingSurveryResult extends Model
{
    public function survey(){
        return $this->belongsTo('App\Survey');
    }

    public function meeting(){
        return $this->belongsTo('App\Meeting');
    }

    public function question(){
        return $this->belongsTo('App\Question');
    }

    public function answerOption(){
        return $this->belongsTo('App\AnswerOption');
    }
}
