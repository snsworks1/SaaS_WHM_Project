<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Service;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPassword;




class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name',
    'email',
    'phone',
    'password',
    'marketing_opt_in',
    'marketing_opt_in_at',
    'customer_type',          // 개인 / 사업자
    'company_name',           // 상호
    'business_number',        // 사업자번호
    'business_address',       // 주소
    'business_type',          // 업태
    'business_item',          // 종목
    'invoice_email',          // 계산서 발행 이메일
];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function plan()
{
    return $this->belongsTo(Plan::class);
}

public function isAdmin()
{
    return $this->is_admin;
}
public function services()
{
    return $this->hasMany(Service::class);
}

public function sendEmailVerificationNotification()
{
    $this->notify(new CustomVerifyEmail);
}

public function sendPasswordResetNotification($token)
{
    $this->notify(new CustomResetPassword($token));
}


}
