<?php

use Illuminate\Database\Seeder;
use App\Question;
use App\Survey;
use App\Meeting;

class MeetingSurveryResultsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Meeting::all() as $meeting){
            if( $meeting->survey ){
                if($meeting->survey->questions->count() > 0){
                    $questions = $meeting->survey->questions;
                    $count = 0;
                    foreach($questions as $question){
                      //  if($count == random_int(0,$questions->count())) break;
                      //  $count++;
                        $answerOptions = $question->answerOptions->count();
                        $meetingResultCount = 2*$answerOptions;
                        factory(App\MeetingSurveryResult::class, $meetingResultCount)->make()->each(function ($answerOption) use($meeting, $question) {
                            $answerOption->survey_id = $meeting->survey->id;
                            $answerOption->meeting_id = $meeting->id;
                            $answerOption->question_id = $question->id;
                            $answerOption->answer_option_id = $question->answerOptions->random()->id;
                            $answerOption->save();
                        });
                    }
                }
            }

        }
    }
}
