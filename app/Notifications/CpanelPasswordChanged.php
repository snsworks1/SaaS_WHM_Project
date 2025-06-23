<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CpanelPasswordChanged extends Notification
{
    protected $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🔐 Hostyle cPanel 비밀번호가 변경되었습니다')
            ->greeting($notifiable->name . '님,')
            ->line('요청하신 cPanel 계정의 비밀번호가 변경되었습니다.')
            ->line("도메인: **{$this->domain}**")
            ->line('본인이 요청하지 않은 경우, 보안을 위해 즉시 고객센터에 문의해주세요.')
            ->line('감사합니다.')
            ->salutation('— Hostyle 보안팀');
    }
}
