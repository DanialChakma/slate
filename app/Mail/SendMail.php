<?php

namespace App\Mail;

use App\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $question;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $sms_content = $this->question->body;
        foreach($this->question->answerOptions as $option){
            $sms_content .="\r\n".trim($option->key).".".trim($option->body);
        }

        return $this->subject($sms_content)->view('email.surveyQuestionTemplate');
    }
}
