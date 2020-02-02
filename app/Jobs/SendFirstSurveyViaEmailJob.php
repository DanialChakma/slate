<?php

namespace App\Jobs;
use App\ClientCompanyContactPerson;
use App\Mail\SendMail;
use App\Meeting;
use App\MeetingSurveryResult;
use App\Question;
use App\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendFirstSurveyViaEmailJob implements ShouldQueue
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
        //
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
            if($meeting && !empty($meeting->survey->id) ){

                $survey = Survey::where('id',$meeting->survey->id)->with(['questions'])
                    ->first();
                $questions = array();
                foreach( $survey->questions as $question){
                    $questions[] = $question->pivot->question_id;
                }

                if( count($questions) > 0 ){

                    /**
                     * First time survey send for this particular meeting
                     * which is already in completed state.
                     */
                    $question = Question::whereIn('id',$questions)->first();

                    $SurveyToSendPersonIDs = array();
                    foreach( $meeting->clientCompanyContactPersons as $person ){
                        $SurveyToSendPersonIDs[]= $person->pivot->client_company_contact_person_id;
                    }

                    if( count($SurveyToSendPersonIDs) > 0 ){
                        $ContactPersons = ClientCompanyContactPerson::whereIn('id',$SurveyToSendPersonIDs)->get();

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

                                            $sms_content = $question->body;
                                            foreach($question->answerOptions as $option){
                                                $sms_content .="\r\n".trim($option->key).".".trim($option->body);
                                            }

                                            $params = array('receivers'=> trim($clientContact),'content'=>trim($sms_content));

                                            try{
                                                //danialchakma120@gmail.com
                                                $newRessult->sent_params_to_sgw = collect($params)->toJson();
                                                $MAIL_DOMAIN = config('mail.MAIL_DOMAIN');
                                                $to_mail = $clientContact."@".$MAIL_DOMAIN;
                                                Mail::to("danialchakma120@gmail.com")->send(new SendMail($question));
                                                $newRessult->save();
                                                unset($params);

                                            }catch (Exception $ex){
                                                Log::error("Error:".$ex->getMessage()."\n");
                                                //echo "Error:".$ex->getMessage()."\n";
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
                }else{
                    Log::info("No Question Founds.\n");
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
