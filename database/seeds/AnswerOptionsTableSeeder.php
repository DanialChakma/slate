<?php

use Illuminate\Database\Seeder;
use App\Question;

class AnswerOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Question::all();
        foreach ($questions as $question) {
            if ( $question->answerOptions->count() < 2 ){
                factory(App\AnswerOption::class, random_int(2, 5))->make()->each(function ($answerOption) use($question) {
                    $answerOption->question_id = $question->id;
                    $answerOption->save();
                });
            }
        }
    }
}
