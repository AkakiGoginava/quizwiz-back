<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
{
    use Queueable;

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

        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(120),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        $parsed = parse_url($signedUrl);
        parse_str($parsed['query'] ?? '', $params);

        $verifyId = $params['id'] ?? '';
        $verifyHash = $params['hash'] ?? '';

        return "{$frontendUrl}/login?verify_id={$verifyId}&verify_hash={$verifyHash}";
    }
}
