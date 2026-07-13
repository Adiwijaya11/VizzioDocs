<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendOtpNotification extends Notification
{
    use Queueable;

    public string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode OTP Atur Ulang Kata Sandi - VizzioDocs')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Anda menerima email ini karena kami menerima permintaan atur ulang kata sandi untuk akun Anda.')
            ->line('Kode OTP 6 digit Anda adalah:')
            ->line('**' . $this->otp . '**')
            ->line('Kode ini berlaku selama 10 menit.')
            ->line('Jika Anda tidak meminta atur ulang kata sandi, abaikan email ini.')
            ->salutation('Salam, Tim VizzioDocs');
    }
}
