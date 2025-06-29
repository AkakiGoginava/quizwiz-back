<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $title = 'Verify your email address to get started';
        $content = "Hi {$notifiable->name},\n\nYou're almost there! To complete the sign up, please verify your email address.";
        $linkName = 'Verify now';

        return (new MailMessage)
            ->subject('Please verify your email')
            ->markdown('email.index', [
                'url'      => $verificationUrl,
                'title'    => $title,
                'content'  => $content,
                'linkName' => $linkName,
            ]);
    }

    protected function verificationUrl($notifiable)
    {
        $frontendUrl = env('FRONTEND_URL');

        return "{$frontendUrl}/login?token={$this->token}";
    }
}
