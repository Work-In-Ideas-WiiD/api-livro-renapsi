<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly string $token,
        public readonly string $email,
        public readonly string $url,
        public readonly string $subject,
        public readonly string $view = 'mail.criar_senha'
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)->view(
            $this->view,
            [
                'url' => $this->url,
                'email' => $this->email,
                'token' => $this->token
            ]
        )
            ->subject($this->subject);
    }
}
