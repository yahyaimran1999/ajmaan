<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetToken;
    public $appName;
    public $resetUrl;

    /**
     * Create a new message instance.
     *
     * @param string $resetToken
     * @return void
     */
    public function __construct($resetToken)
    {
        $this->resetToken = $resetToken;
        $this->appName = config('app.name');
        $this->resetUrl = config('app.frontend_url') . '/reset-password?token=' . $resetToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset Your Password')
                    ->view('emails.password-reset');
    }
}
