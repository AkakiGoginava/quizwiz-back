<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = 'Click the link to reset your password';
        $content = "Hi {$notifiable->name},\n\nto reset your password, please follow the link.";
        $linkName = 'Reset password';

        return (new MailMessage)
            ->subject('Reset your password')
            ->markdown('email.index', [
                'url'      => $this->url,
                'title'    => $title,
                'content'  => $content,
                'linkName' => $linkName,
            ]);
    }
}
