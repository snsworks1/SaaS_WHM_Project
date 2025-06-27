<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
{
    Validator::make($input, [
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'phone' => ['required', 'regex:/^01[0-9]{8,9}$/'],
    'password' => $this->passwordRules(),

    'customer_type' => ['required', 'in:personal,business'],

    // 사업자 고객일 때만 필수
    'company_name' => ['required_if:customer_type,business', 'string', 'max:255'],
    'business_number' => ['required_if:customer_type,business', 'string', 'max:30'],
    'business_address' => ['required_if:customer_type,business', 'string', 'max:255'],
    'business_type' => ['required_if:customer_type,business', 'string', 'max:100'],
    'business_item' => ['required_if:customer_type,business', 'string', 'max:100'],
    'invoice_email' => ['required_if:customer_type,business', 'email', 'max:255'],
])->validate();

    return User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'phone' => $input['phone'],
        'password' => Hash::make($input['password']),
        'marketing_opt_in' => isset($input['marketing_opt_in']) ? 1 : 0,
        'marketing_opt_in_at' => isset($input['marketing_opt_in']) ? now() : null,

        // ✅ 여기에도 저장 추가
        'customer_type' => $input['customer_type'] ?? 'personal',
        'company_name' => $input['company_name'] ?? null,
        'business_number' => $input['business_number'] ?? null,
        'business_address' => $input['business_address'] ?? null,
        'business_type' => $input['business_type'] ?? null,
        'business_item' => $input['business_item'] ?? null,
        'invoice_email' => $input['invoice_email'] ?? null,
    ]);
}
}
