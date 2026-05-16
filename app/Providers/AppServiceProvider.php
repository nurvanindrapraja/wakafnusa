<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Verifikasi Alamat Email — WAKAF NUSA')
                ->greeting('Assalamu\'alaikum, ' . $notifiable->name)
                ->line('Terima kasih telah mendaftar di WAKAF NUSA (Platform Wakaf Nahdlatul Ulama).')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan mengaktifkan akun Anda.')
                ->action('Verifikasi Email', $url)
                ->line('Jika Anda tidak merasa mendaftar di layanan kami, silakan abaikan pesan ini.')
                ->salutation('Wassalamu\'alaikum,');
        });
    }
}
