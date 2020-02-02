<?php

namespace App\Http\Controllers;

use App\MeetingSurveryResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Mockery\CountValidator\Exception;
use App\Jobs\SendPendingSmsJob;

class SmsgwController extends Controller
{
    //
    public function checkBalance(){
        $SMSGW_URL = config('sgw.url');
        $APP_ID = config('sgw.appid');
        $APP_SECRET = config('sgw.appsecret');

        $PendingSmsCount = MeetingSurveryResult::where('failed_for_no_balance',true)->count();
        $hasPendingSms = $PendingSmsCount > 0 ? true:false;


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
        }

        if( $CreditBalance > 0 ){
            return collect(array("status"=>"OK","HasPending"=>$hasPendingSms,"Balance"=>$CreditBalance,"message"=>"You have available balance."))->toJson();
        }else{
            return collect(array("status"=>"NOK","HasPending"=>$hasPendingSms,"Balance"=>$CreditBalance,"message"=>"You have no available balance."))->toJson();
        }
    }

    public function sendPendingSMSes()
    {
       // Cache::forget('SendingPendingSMS');
        $expiresAt = Carbon::now()->addMinutes(1);
        if( Cache::has('SendingPendingSMS') ){
            return collect(array("status"=>"NOK","message"=>"Sending Pending SMSes..."))->toJson();
        }else{
            Cache::add('SendingPendingSMS',true,$expiresAt);
            SendPendingSmsJob::dispatch()->onConnection('database')->onQueue('SendPendingSMS');
            return collect(array("status"=>"OK","message"=>"Sending Pending SMSes Task has been queued."))->toJson();
        }
    }
}
