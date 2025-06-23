<?php
namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🎉 [Hostyle] 이메일 인증을 완료해주세요!')
            ->markdown('emails.verify', [
                'user' => $notifiable,
                'verificationUrl' => $verificationUrl,
            ]);
    }
}
