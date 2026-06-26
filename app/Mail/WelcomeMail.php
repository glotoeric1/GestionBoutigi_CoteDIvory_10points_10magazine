<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $fullname, $messages, $messages2, $routes, $btn, $subjects, $froms, $user, $pass;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fullname, $messages,  $messages2, $routes, $btn, $subjects, $user='', $pass='', $froms='')
    {
        $this->fullname=$fullname;
        $this->messages=$messages;
        $this->messages2=$messages2;
        $this->routes=$routes;
        $this->btn=$btn;
        $this->subjects=$subjects;
        $this->froms=$froms;
        $this->user=$user;
        $this->pass=$pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(empty($this->froms)){
            return $this->subject($this->subjects)->view('email.email');  
        }
        return $this->from($this->froms)->subject($this->subjects)->view('email.email');
    }
}

