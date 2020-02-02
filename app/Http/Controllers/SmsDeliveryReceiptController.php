<?php

namespace App\Http\Controllers;
use App\MeetingSurveryResult;
use App\Jobs\ProcessSurveyResponse;
use Illuminate\Http\Request;

class SmsDeliveryReceiptController extends Controller
{
    //

    public function smsDeliveryReceipt(){

        $MessageID = request()->get('MessageID');
        $Status = request()->get('Status');
        $UpdatedOn = request()->get('UpdatedOn');

        $MessageID = trim($MessageID);
        $Status = trim($Status);
        $UpdatedOn = trim($UpdatedOn);

        if( !empty($MessageID) && !empty($Status) ){

             $result = MeetingSurveryResult::where('sgw_message_id','=',$MessageID)
                                            ->orderBy('created_at','DESC')
                                            ->first();

            if( $result && $result->exists() ){

                $status_array = array('PROCESSED','SENT','RECEIVED','FAILED');

                if( trim($result->sgw_status) === "RECEIVED" ||  trim($result->sgw_status) === "FAILED" ){
                    // ignore everything bcoz already received message delivery status.(ie. RECEIVED OR FAILED)
                    return "OK";
                   // return collect(array('Status'=>"OK",'Message'=>"Already,Acknowledged SMS Delivery Report(RECEIVED|FAILED)."))->toJson();
                }elseif( trim($result->sgw_status) === "SENT" ){
                    // update incoming status for RECEIVED OR FAILED
                    if( $Status === "RECEIVED" || $Status === "FAILED" ){
                        $result->sgw_status = $Status;
                        $request_data = array(
                            'MessageID'=>$MessageID,
                            'Status'=>$Status,
                            'UpdatedOn'=>$UpdatedOn
                        );

                        $result->sgw_delivery_receipts .= '|' . collect($request_data)->toJson();
                        $result->save();
                        return "OK";
                       // return collect(array('Status'=>"OK",'Message'=>"Acknowledged SMS Delivery Report Successfully."))->toJson();
                    }

                }elseif( trim($result->sgw_status) === "PROCESSED" ){
                    // update incoming status whether it is SENT,RECEIVED,OR FAILED

                    if( $Status === "SENT" || $Status === "RECEIVED" || $Status === "FAILED" ){
                        $result->sgw_status = $Status;
                        $request_data = array(
                            'MessageID'=>$MessageID,
                            'Status'=>$Status,
                            'UpdatedOn'=>$UpdatedOn
                        );

                        $result->sgw_delivery_receipts .= '|' . collect($request_data)->toJson();
                        $result->save();
                        return "OK";
                        //return collect(array('Status'=>"OK",'Message'=>"Acknowledged SMS Delivery Report Successfully."))->toJson();
                    }
                }elseif( in_array($Status,$status_array) && !in_array(trim($result->sgw_status),$status_array) ){
                    // update for the first time.
                    $result->sgw_status = $Status;
                    $request_data = array(
                        'MessageID'=>$MessageID,
                        'Status'=>$Status,
                        'UpdatedOn'=>$UpdatedOn
                    );

                    $result->sgw_delivery_receipts .= '|' . collect($request_data)->toJson();
                    $result->save();
                    return "OK";
                   // return collect(array('Status'=>"OK",'Message'=>"Acknowledged SMS Delivery Report Successfully."))->toJson();
                }else{
                    return "FAILED|Inconsistent SMS Delivery Report.";
                    //return collect(array('Status'=>"FAILED",'Message'=>"Inconsistent SMS Delivery Report."))->toJson();
                }

            }else{

                return "FAILED|MessageID not found.";
                // return collect(array('Status'=>"FAILED",'Message'=>"MessageID not found."))->toJson();
            }

        }else{
            return "FAILED|Invalid,Request Parameter.";
           // return collect(array('Status'=>"FAILED",'Message'=>"Invalid,Request Parameter."))->toJson();
        }
    }


    public function answerCallback(){
        $params = request()->input();

        $param_number = count($params);
        if( $param_number <= 1 ||  $param_number >2 ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request: Incorrect number of parameter."
            );
            return collect($response_array)->toJson();
        }
        if( ! request()->has('mobileno') || !request()->has('message') ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request. Missing required parameter."
            );
            return collect($response_array)->toJson();
        }
        $msisdn = request()->input('mobileno');
        $answer = request()->input('message');
        $msisdn = urldecode($msisdn);
        $answer = urldecode($answer);
        $msisdn = trim($msisdn);
        $answer = trim($answer);
        if( empty($msisdn)||empty($answer) ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request. Missing required parameter values."
            );
            return collect($response_array)->toJson();
        }else{
            ProcessSurveyResponse::dispatch($msisdn,$answer)
                                    ->onConnection('database')
                                    ->onQueue('answerCallback');
            $response_array = array(
                "status"=>"OK", "message"=>"Thanks,for giving us response."
            );
            return collect($response_array)->toJson();
        }
    }

    public function callback(){
        $params = request()->input();
        $param_number = count($params);
        if( $param_number <= 1 ||  $param_number >3 ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request: Incorrect number of parameter."
            );
            return collect($response_array)->toJson();
        }
        if( ! request()->has('MobileNo') || !request()->has('Message') ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request. Missing required parameter."
            );
            return collect($response_array)->toJson();
        }

        $msisdn = request()->input('MobileNo');
        $answer = request()->input('Message');
        $msisdn = urldecode($msisdn);
        $answer = urldecode($answer);
        $msisdn = trim($msisdn);
        $answer = trim($answer);
        if( empty($msisdn)||empty($answer) ){
            $response_array = array(
                "status"=>"FAILED", "message"=>"Incorrect request. Missing required parameter values."
            );
            return collect($response_array)->toJson();
        }else{
            ProcessSurveyResponse::dispatch($msisdn,$answer)
                ->onConnection('answerCallback')
                ->onQueue('answerCallback');
            $response_array = array(
                "status"=>"OK", "message"=>"Thanks,for giving us response."
            );
            return collect($response_array)->toJson();
        }
    }

}
