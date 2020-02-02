<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
class MailController extends Controller
{
    //

    public function sendEmail(){

        Mail::to("danialchakma120@gmail.com")->send(new SendMail(Question::first()));
    }
}
