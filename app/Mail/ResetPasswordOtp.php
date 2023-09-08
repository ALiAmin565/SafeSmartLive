<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $otpDataaa;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otpData)
    {
        $this->otpDataaa = $otpData;
        // attach qrimage to mail


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("reset password")
                    ->view("otpmail");
    }
}
