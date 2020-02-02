<?php
namespace App\Console\Commands;
use App\Meeting;
use App\ClientCompanyContactPerson;
use App\MeetingSurveryResult;
use GuzzleHttp;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Exception;

class SendSurveyForMeetingCommand implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $meeting;

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
    public function __construct($meeting)
    {
        $this->meeting = $meeting;
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

            $meeting = Meeting::find($this->meeting->id);
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


                        // echo "First time survey send for this meeting\n";
                        /**
                         * First time survey send for this particular meeting
                         * which is already in completed state.
                         */
                        $question = $meeting->survey->questions()->first();
                        $question_count = $meeting->survey->questions()->count();
                        $SurveyToSendPersonIDs = array();
                        foreach( $meeting->clientCompanyContactPersons as $person ){
                            $SurveyToSendPersonIDs[]= $person->pivot->client_company_contact_person_id;
                        }

                        if( count($SurveyToSendPersonIDs) > 0 ){
                            $ContactPersons = ClientCompanyContactPerson::whereIn('id',$SurveyToSendPersonIDs)->get();
                            $CreditBalance = 0;
                            $params = array(
                                'appid'=>trim($APP_ID),
                                'appsecret'=>trim($APP_SECRET),
                                'responseformat'=>'JSON'
                            );
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

                            foreach($ContactPersons as $ContactPerson){

                                try{

                                    if( $ContactPerson && $ContactPerson->exists() ){
                                        $clientContact = $ContactPerson->phone;
                                        $newRessult =  new MeetingSurveryResult();
                                        $newRessult->survey_id = $meeting->survey->id;
                                        $newRessult->meeting_id = $meeting->id;
                                        $newRessult->question_id = $question->id;
                                        $newRessult->msisdn = $clientContact;

                                        if( $newRessult->save() ){

                                            if( $SmsSendCount >= $CreditBalance ){
                                                $newRessult->failed_for_no_balance = true;
                                                $newRessult->save();
                                            }else{
                                                $sms_content = $question->body;
                                                foreach($question->answerOptions as $option){
                                                    $sms_content .="\r\n".trim($option->key).".".trim($option->body);
                                                }

                                                $params = array('appid'=>trim($APP_ID),'appsecret'=>trim($APP_SECRET),'responseformat'=>'JSON','receivers'=> trim($clientContact),'content'=>trim($sms_content));
                                                $param_string = http_build_query($params);

                                                try{

                                                    $newRessult->sent_params_to_sgw = collect($params)->toJson();
                                                    $newRessult->save();
                                                    $client = new Client();
                                                    $response = $client->get($SMSGW_URL.'?'.$param_string);
                                                    $smsgw_response = $response->getBody()->getContents();
                                                    $ResponseBodyJson = json_decode($smsgw_response, true);// convert json string to associative array.
                                                    if( $ResponseBodyJson && is_array($ResponseBodyJson) ){
                                                        if(array_key_exists('result',$ResponseBodyJson) && is_array($ResponseBodyJson['result']) ){

                                                            if(array_key_exists('status',$ResponseBodyJson['result'])){

                                                                $status = $ResponseBodyJson['result']['status'];
                                                                if( $status === "OK" ){
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
                                                                    if( array_key_exists('error',$ResponseBodyJson['result']) ){
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
                                                        $newRessult->sgw_response = trim($smsgw_response);
                                                        $newRessult->save();
                                                        Log::error("SMS Gateway Response JSON Decode Error:".json_last_error_msg()."\n");
                                                    }

                                                    Log::info("SMS Gateway Response:".$smsgw_response."\n");

                                                    unset($params);
                                                    unset($param_string);
                                                }catch (Exception $ex){
                                                    Log::error("Error:".$ex->getMessage()."\n");
                                                    //echo "Error:".$ex->getMessage()."\n";
                                                }
                                            }
                                        }
                                    }

                                    if(isset($ContactPerson))unset($ContactPerson);
                                }catch(Exception $ex){
                                    Log::error("Error:".$ex->getMessage()."\n");
                                    continue;
                                }
                            }

                            if(isset($SurveyToSendPersonIDs))unset($SurveyToSendPersonIDs);
                            if(isset($question))unset($question);
                        }else{
                            Log::info("No conatct person to send survey.\n");
                        }
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
