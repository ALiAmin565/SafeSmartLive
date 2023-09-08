<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otpDataa)
    {
        $this->otpData = $otpDataa;
        // attach qrimage to mail


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("verify Email")
                    ->view("otpmail");
    }
}