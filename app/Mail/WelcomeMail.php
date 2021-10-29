<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
	public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$address = 'noreply@kultprit.openlogicsys.com';
        $subject = 'Welcome to Kultprit';
        $name = 'Kultprit';
        
        return $this->view('emails.welcome')
                    ->from($address, $name)
                    ->subject($subject);
    }
}