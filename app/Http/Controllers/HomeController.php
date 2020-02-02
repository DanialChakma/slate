<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function toggleNotificationBox(Request $request){
        if($request->session()->has('notification_box_enabled')){
            $is_enabled = $request->session()->get('notification_box_enabled');
            $request->session()->put('notification_box_enabled',!$is_enabled);
            return collect(array("status"=>true,"message"=>"Notification Successfully Toggled."))->toJson();

        }else{
            $request->session()->put('notification_box_enabled',false);
            return collect(array("status"=>true,"message"=>"Notification Disabled. You will see notification when you will login again."))->toJson();

        }
    }
}
