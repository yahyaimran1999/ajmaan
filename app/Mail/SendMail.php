<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $template_content;
    public $data;
    public $user_email;

    public function __construct($user_email, $subject, $template_content, $data = [])
    {
        $this->user_email = $user_email;
        $this->subject = $subject;
        $this->template_content = $template_content;
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->html($this->template_content)
                    ->with($this->data);
    }
}
