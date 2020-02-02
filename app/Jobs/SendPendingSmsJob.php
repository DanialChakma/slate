<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\MeetingSurveryResult;
use GuzzleHttp\Client;

class SendPendingSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $SMSGW_URL = config('sgw.url');
        $APP_ID = config('sgw.appid');
        $APP_SECRET = config('sgw.appsecret');
        $pendingResults = MeetingSurveryResult::all()->where('failed_for_no_balance',true);
        foreach( $pendingResults as $result ){
            $msisdn = $result->msisdn;
            $question = $result->question()->first();
            $sms_content = $question->body;
            foreach($question->answerOptions as $option){
                $sms_content .="\r\n".trim($option->key).".".trim($option->body);
            }

            $params = array(    'appid'=>trim($APP_ID),
                'appsecret'=>trim($APP_SECRET),
                'responseformat'=>'JSON',
                'receivers'=> trim($msisdn),
                'content'=>trim($sms_content)
            );

            $param_string = http_build_query($params);

            try{
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
                                        $result->sgw_message_id = $message_id;
                                        $result->sgw_response = trim($smsgw_response);
                                        $result->failed_for_no_balance = false;
                                        $result->save();
                                    }
                                }
                            }else{
                                //perhaps some error ie. 'NOK' status.
                                // check if user have enough balance in smsgw
                                if( array_key_exists('error',$ResponseBodyJson['result']) ){
                                    $errorMessage = trim($ResponseBodyJson['result']['error']);
                                    $for_credit_string = "Not enough credits";
                                    if( strpos($errorMessage,$for_credit_string) !== false ){
                                        $result->failed_for_no_balance = true;
                                    }else{
                                        $result->failed_for_other_reason = true;
                                    }
                                }

                                $result->sgw_response = trim($smsgw_response);
                                $result->save();
                            }
                        }
                    }
                }else{
                    $result->sgw_response = trim($smsgw_response);
                    $result->save();
                    Log::error("SMS Gateway Response JSON Decode Error:".json_last_error_msg()."\n");
                }

            }catch(Exception $ex){
                Log::error("Error:".$ex->getMessage()."\n");
                continue;
            }
        }


        if(Cache::has('SendingPendingSMS'))Cache::forget('SendingPendingSMS');


    }
}
