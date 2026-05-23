<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordIndonesian extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Notifikasi Pemulihan Password - Wakaf Nusa')
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan pemulihan password untuk akun Anda.')
            ->action('Atur Ulang Password', $url)
            ->line('Tautan pemulihan password ini akan kedaluwarsa dalam ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60) . ' menit.')
            ->line('Jika Anda tidak meminta pemulihan password, Anda tidak perlu melakukan tindakan lebih lanjut.')
            ->salutation("Salam hangat,\nTim Wakaf Nusa");
    }
}
