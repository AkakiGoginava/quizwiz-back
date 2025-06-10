<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $title = 'Verify your email address to get started';
            $content = "Hi {$notifiable->name},\n\nYou're almost there! To complete the sign up, please verify your email address.";
            $linkName = 'Verify now';

            return (new MailMessage)

                ->subject('Please verify your email')

                ->markdown('email.index', [
                    'url'      => $url,
                    'title'    => $title,
                    'content'  => $content,
                    'linkName' => $linkName,
                ]);
        });
    }
}
