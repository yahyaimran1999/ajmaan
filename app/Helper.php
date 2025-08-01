<?php

namespace App;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class Helper
{
    /**
     * Send an email using the specified template
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $content Email content/body
     * @param array $data Additional data for the template
     * @return void
     */
    public static function SendEmail($to, $subject, $content, $data = [])
    {
        try {
            $email = new SendMail($to, $subject, $content, $data);
            Mail::to($to)->send($email);
            return true;
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
