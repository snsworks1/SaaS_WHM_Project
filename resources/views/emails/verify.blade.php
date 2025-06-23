@component('mail::message')
<div style="text-align: center; margin-bottom: 24px;">
    <img src="{{ asset('images/logo.png') }}" alt="Hostyle 로고" style="height: 64px;">
</div>

# 🎉 {{ $user->name }}님, 환영합니다!

<div style="margin-top: 12px; line-height: 1.8; font-size: 16px;">
    <strong>Hostyle</strong>에 가입해주셔서 감사합니다.<br>
    아래 버튼을 클릭하여 이메일 인증을 완료해주세요.
</div>

@component('mail::button', ['url' => $verificationUrl, 'color' => 'blue'])
이메일 인증하기
@endcomponent

<div style="margin-top: 20px; font-size: 14px; color: #555;">
    이 요청을 하지 않으셨다면 이 이메일은 무시하셔도 됩니다.
</div>

<div style="margin-top: 24px;">
    감사합니다.  
    <strong>Hostyle 팀 드림</strong>
</div>

---

<div style="font-size: 12px; color: #999; line-height: 1.6; margin-top: 30px; text-align: center;">
    S&S Works | 대표 김대현<br>
    서울특별시 강남구 강남대로 112길 47, 2층 369A호<br>
    <a href="mailto:support@hostyle.me" style="color: #999;">support@hostyle.me</a><br>
    <div style="margin-top: 8px;">© 2025 Hostyle. 모든 권리 보유.</div>
</div>
@endcomponent
