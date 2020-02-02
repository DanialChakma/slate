<?php

namespace App\Jobs;

use App\Mail\SendMail;
use App\Meeting;
use App\MeetingSurveryResult;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSurveyViaEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $meeting;
    protected $msisdn;

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
     *
     * @return void
     */
    public function __construct($meeting,$msisdn)
    {
        //
        $this->meeting = $meeting;
        $this->msisdn  = $msisdn;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * this method handles all incoming command to send survey of a particular meeting.
         */
        try{

            $meeting = Meeting::find($this->meeting);
            if($meeting && $meeting->survey && !empty($meeting->survey->id) ){
                if( $meeting->survey->questions->count() > 0 ){

                    $thisMeetingSurveyResult = $meeting->survey->meetingSurveryResults()
                                                                ->where('meeting_id',$meeting->id)
                                                                ->where('survey_id',$meeting->survey->id);
                    $resultCount = $thisMeetingSurveyResult ? $thisMeetingSurveyResult->count(): 0;
                    if(isset($thisMeetingSurveyResult))unset($thisMeetingSurveyResult);

                    if( $resultCount > 0){
                        $meetingResult = $meeting->survey->meetingSurveryResults()
                                                            ->where('meeting_id',$meeting->id)
                                                            ->where('survey_id',$meeting->survey->id)
                                                            ->where('msisdn',$this->msisdn)
                                                            ->orderBy('id','desc')
                                                            ->first();
                        /***
                         *if last question of this survey meeting is not answered
                         *then do nothing until last question answered.
                         *
                         ***/
                        if( $meetingResult && ! empty($meetingResult->answer_option_id) ){
                            // trigger next questions
                            $AnswerdQuestionIDs = array();
                            $AnsweredSets = $meeting->survey->meetingSurveryResults()
                                                            ->where('meeting_id',$meeting->id)
                                                            ->where('survey_id',$meeting->survey->id)
                                                            ->where('msisdn',$this->msisdn)
                                                            ->where(function($query){
                                                                $query->whereNotNull('answer_option_id')
                                                                      ->orWhereNotNull('user_input');
                                                            })
                                                            ->get();
                            foreach($AnsweredSets as $result){
                                $AnswerdQuestionIDs[]= $result->question_id;
                            }
                            $restQuestions = $meeting->survey->questions()->whereNotIn('id',$AnswerdQuestionIDs);
                            unset($AnswerdQuestionIDs);
                            unset($AnsweredSets);
                            /****
                             * if rest question is not empty trigger next question from rest question sets
                             */
                            if($restQuestions && $restQuestions->count() >0 ){
                                $restQuestionCount = $restQuestions->count();
                                $nextQuestionToSend = $restQuestions->first();
                                unset($restQuestions);

                                try{
                                    $clientContact = $this->msisdn;
                                    $newRessult =  new MeetingSurveryResult();
                                    $newRessult->survey_id = $meeting->survey->id;
                                    $newRessult->meeting_id = $meeting->id;
                                    $newRessult->question_id = $nextQuestionToSend->id;
                                    $newRessult->msisdn = $clientContact;
                                    if( $newRessult->save() ){

                                            $sms_content = $nextQuestionToSend->body;
                                            foreach($nextQuestionToSend->answerOptions as $option){
                                                $sms_content .="\r\n".trim($option->key).".".trim($option->body);
                                            }
                                            $params = array(
                                                'receivers'=> trim($clientContact),
                                                'content'=>trim($sms_content)
                                            );

                                            try{
                                                //danialchakma120@gmail.com
                                                $newRessult->sent_params_to_sgw = collect($params)->toJson();
                                                $MAIL_DOMAIN = config('mail.MAIL_DOMAIN');
                                                $to_mail = $clientContact."@".$MAIL_DOMAIN;
                                                //"danialchakma120@gmail.com"
                                                Mail::to($to_mail)->send(new SendMail($nextQuestionToSend));
                                                $newRessult->save();
                                                unset($params);
                                            }catch (Exception $ex){
                                                Log::error("Error:".$ex->getMessage()."\n");
                                                // echo "Error:".$ex->getMessage()."\n";
                                            }

                                    }else{
                                        Log::info("Unable to save Meeting survey result.\n");
                                        // echo "Unable to save Meeting survey result.\n";
                                    }

                                    if(isset($newRessult))unset($newRessult);


                                }catch(Exception $ex){
                                    Log::error("Error:".$ex->getMessage()."\n");

                                }

                                if(isset($nextQuestionToSend))unset($nextQuestionToSend);
                                if(isset($restQuestionCount)) unset($restQuestionCount);
                            }else{

                                /**
                                 * Perhaps,All question is answered
                                 *
                                 */
                                Log::info("There is no question to send.Perhaps,All question is answered.\n");
                                //echo "There is no question to send.\n";
                            }
                        }
                    }
                }else{
                    Log::info("There is no survey question.\n");
                    // echo "survey question zero\n";
                }
            }else{
                Log::info("Survey Not founds OR Survey ID is empty\n");
                //echo "survey id is empty\n";
            }

            // echo $meeting->location."\n";
        }catch (Exception $ex){
            Log::error("Error happended.\nError:".$ex->getMessage()."\n");
            //echo "Error happened.\n" .$ex->getMessage()."\n";
        }
    }
}
