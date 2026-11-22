<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedNotification extends Notification
{
    use Queueable;

    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = trim(
            ($notifiable->first_name ?? '') . ' ' .
            ($notifiable->last_name ?? '')
        );

        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Your UCU Smart+ Account Is Ready')
            ->greeting('Welcome to UCU Smart+, ' . ($name ?: 'User') . '!')
            ->line('An administrator has created an account for you.')
            ->line('Your username is: ' . $notifiable->username)
            ->line('Before logging in, please set your password using the secure link below.')
            ->action('Set Your Password', $resetUrl)
            ->line('For your security, this link is temporary and can only be used once.')
            ->salutation('Regards, UCU Smart+ Team');
    }
}
