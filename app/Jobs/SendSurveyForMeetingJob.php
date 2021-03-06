<?php

namespace App\Jobs;

use App\Meeting;
use App\ClientCompanyContactPerson;
use App\MeetingSurveryResult;
use GuzzleHttp;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendSurveyForMeetingJob implements ShouldQueue
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
                    $SMSGW_URL = config('sgw.url');
                    $APP_ID = config('sgw.appid');
                    $APP_SECRET = config('sgw.appsecret');
                    $thisMeetingSurveyResult = $meeting->survey->meetingSurveryResults()
                                                                ->where('meeting_id',$meeting->id)
                                                                ->where('survey_id',$meeting->survey->id);
                    $resultCount = $thisMeetingSurveyResult ? $thisMeetingSurveyResult->count(): 0;
                    if(isset($thisMeetingSurveyResult))unset($thisMeetingSurveyResult);
                    //echo $meeting->id.":".$meeting->title ." , c=".$resultCount."\n";
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
                                ->whereNotNull('answer_option_id')
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
                                $CreditBalance = 0;
                                $params = array('appid'=>trim($APP_ID),'appsecret'=>trim($APP_SECRET),'responseformat'=>'JSON');
                                $param_string = http_build_query($params);
                                $client = new Client();
                                $response = $client->get($SMSGW_URL.'?'.$param_string);
                                $smsgw_response = $response->getBody()->getContents();
                                $ResponseBodyJson = json_decode($smsgw_response, true);
                                if( $ResponseBodyJson && is_array($ResponseBodyJson) ){
                                    if(array_key_exists('credit',$ResponseBodyJson) && is_array($ResponseBodyJson['credit']) ){
                                        if(array_key_exists('balance',$ResponseBodyJson['credit'])){
                                            $CreditBalance = is_numeric($ResponseBodyJson['credit']['balance']) ? intval($ResponseBodyJson['credit']['balance']):0;
                                        }
                                    }
                                }else{
                                    Log::error("SMS Gateway Response JSON Decode Error:".json_last_error_msg()."\n");
                                }

                                $SmsSendCount = 0;
                                try{
                                    $clientContact = $this->msisdn;
                                    $newRessult =  new MeetingSurveryResult();
                                    $newRessult->survey_id = $meeting->survey->id;
                                    $newRessult->meeting_id = $meeting->id;
                                    $newRessult->question_id = $nextQuestionToSend->id;
                                    $newRessult->msisdn = $clientContact;
                                    if( $newRessult->save() ){

                                        if( ($SmsSendCount >= $CreditBalance) ){
                                            $newRessult->failed_for_no_balance = true;
                                            $newRessult->save();
                                        }else{
                                            $sms_content = $nextQuestionToSend->body;
                                            foreach($nextQuestionToSend->answerOptions as $option){
                                                $sms_content .="\r\n".trim($option->key).".".trim($option->body);
                                            }
                                            $params = array('appid'=>trim($APP_ID),'appsecret'=>trim($APP_SECRET),'responseformat'=>'JSON',
                                                'receivers'=> trim($clientContact),
                                                'content'=>trim($sms_content)
                                            );

                                            try{

                                                $param_string = http_build_query($params);
                                                $newRessult->sent_params_to_sgw = collect($params)->toJson();
                                                $newRessult->save();
                                                $client = new Client();
                                                $response = $client->get($SMSGW_URL.'?'.$param_string);

                                                $smsgw_response = $response->getBody()->getContents();
                                                $ResponseBodyJson = json_decode($smsgw_response, true);

                                                if( $ResponseBodyJson && is_array($ResponseBodyJson) ){
                                                    if(array_key_exists('result',$ResponseBodyJson) && is_array($ResponseBodyJson['result']) ){

                                                        if(array_key_exists('status',$ResponseBodyJson['result'])){
                                                            $status = $ResponseBodyJson['result']['status'];
                                                            if( $status == "OK" ){
                                                                if( array_key_exists('receivers', $ResponseBodyJson) && is_array($ResponseBodyJson['receivers']) && count($ResponseBodyJson['receivers']) >0 ){
                                                                    if( is_array($ResponseBodyJson['receivers'][0]) && array_key_exists('messageid',$ResponseBodyJson['receivers'][0]) ){
                                                                        $message_id = $ResponseBodyJson['receivers'][0]['messageid'];
                                                                        $newRessult->sgw_message_id = $message_id;
                                                                        $newRessult->sgw_response = trim($smsgw_response);
                                                                        $newRessult->save();
                                                                        $SmsSendCount++;
                                                                    }
                                                                }
                                                            }else{
                                                                //perhaps some error ie. 'NOK' status.
                                                                // check if user have enough balance in smsgw
                                                                if(array_key_exists('error',$ResponseBodyJson['result'])){
                                                                    $errorMessage = trim($ResponseBodyJson['result']['error']);
                                                                    $for_credit_string = "Not enough credits";
                                                                    if( strpos($errorMessage,$for_credit_string) !== false ){
                                                                        $newRessult->failed_for_no_balance = true;
                                                                    }else{
                                                                        $newRessult->failed_for_other_reason = true;
                                                                    }
                                                                }

                                                                $newRessult->sgw_response = trim($smsgw_response);
                                                                $newRessult->save();
                                                            }
                                                        }
                                                    }
                                                }else{
                                                    Log::error("SMS Gateway Response JSON Decode Error:".json_last_error_msg()."\n");
                                                }


                                                Log::info("SMS Gateway Response:".$smsgw_response."\n");
                                                unset($param_string);
                                                unset($params);
                                            }catch (Exception $ex){
                                                Log::error("Error:".$ex->getMessage()."\n");
                                                // echo "Error:".$ex->getMessage()."\n";
                                            }
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
