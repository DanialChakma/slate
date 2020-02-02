<?php

namespace App\Jobs;
use App\Meeting;
use App\MeetingSurveryResult;
use App\AnswerOption;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Console\Commands\SendSurveyForMeetingCommand;
class ProcessSurveyResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /***
     * Contact Or Phone number of client company
     * @var string
     *
     */
    protected $msisdn;
    /**
     * AnswerKey response of survey question
     * from the contact person.
     * @var
     *
     */
    protected $AnswerKey;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;


    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     *
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     * @param msisdn
     * @param AnswerKey
     * @return void
     */
    public function __construct($msisdn,$answer)
    {
        $this->msisdn = $msisdn;
        $this->AnswerKey = $answer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $msisdn = $this->msisdn;
        $answer = $this->AnswerKey;

        $lastSurveyResult =  MeetingSurveryResult::where('msisdn','=',$msisdn)
                                                    ->where(function($query){
                                                        $query->whereNull('answer_option_id')->WhereNull('user_input');
                                                    })
                                                    ->whereNotNull('meeting_id')
                                                    ->whereNotNull('survey_id')
                                                    ->orderBy('created_at','DESC')
                                                    ->first();

        if( $lastSurveyResult && $lastSurveyResult->exists() ){

            $survey = $lastSurveyResult->survey()->where('id',$lastSurveyResult->survey_id)->first();
            if( $survey && $survey->questions()->count() > 0 ){
                $questions = $survey->questions();
                $request_question = $questions->where('id',$lastSurveyResult->question_id)->first();
                if( $request_question && $request_question->type == "Open-text" ){
                    $lastSurveyResult->user_input = $answer;
                    $lastSurveyResult->save();
                } else if( $request_question && $request_question->answerOptions()->count() > 0 ){
                    $option_via_eager_method = $request_question->answerOptions()->where('key',$answer)
                                                                                    ->orderBy('id')
                                                                                        ->first();
                    $option_via_model_query = AnswerOption::where('question_id',$lastSurveyResult->question_id)
                                                                ->where('key','=',$answer)
                                                                ->orderBy('id')
                                                                ->first();

                    if( $option_via_eager_method && $option_via_model_query ){

                        $id_via_eager = $option_via_eager_method->exists() ? $option_via_eager_method->id : null;
                        $id_via_model_query = $option_via_model_query->exists() ? $option_via_model_query->id : null;

                        if( $id_via_eager && $id_via_model_query ){
                            $is_id_matched =  $id_via_eager == $id_via_model_query ? true : false;
                            if($is_id_matched){
                                $lastSurveyResult->answer_option_id = $id_via_model_query;
                                if( $lastSurveyResult->save() ){
                                    /**
                                     * if successfully saved answer option id
                                     * then check if any overdue meeting is present.
                                     * overdue meeting is defined as the meetings whose survey response is still pending
                                     * or partially answered(not all question response received at all for all the client contact person)
                                     */
                                    $due_meetings = MeetingSurveryResult::where('msisdn','=',$msisdn)
                                                                            ->whereNotNull('meeting_id')
                                                                            ->where('meeting_id','<>',$lastSurveyResult->meeting_id)
                                                                            ->whereNotNull('survey_id')
                                                                            ->where('survey_id','<>',$lastSurveyResult->survey_id)
                                                                            ->get();
                                    if( $due_meetings && $due_meetings->count() >0 ){
                                            foreach($due_meetings as $meetingResult){
                                                Meeting::where('id',$meetingResult->meeting_id)
                                                            ->where('survey_id',$meetingResult->survey_id)
                                                            ->where('survey_duration_over',false)
                                                            ->update(['survey_duration_over'=>true]);
                                            }
                                    }
                                }
                            }
                        }
                    }
                }

            }

            SendSurveyViaEmailJob::dispatch($lastSurveyResult->meeting_id,$lastSurveyResult->msisdn)
                                    ->onConnection('answerCallback')
                                    ->onQueue('answerCallback');
//            SendSurveyForMeetingJob::dispatch($lastSurveyResult->meeting_id,$lastSurveyResult->msisdn)
//                                    ->onConnection('database')->onQueue('SurveySend');

        }
    }
}
