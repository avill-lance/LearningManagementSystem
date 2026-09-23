<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'     => ['required', 'string', 'max:50', 'regex:/^[\p{L}\s.\'-]+$/u'],
            'last_name'      => ['required', 'string', 'max:50', 'regex:/^[\p{L}\s.\'-]+$/u'],
            'email'          => ['required', 'email:rfc', 'max:100', 'unique:teachers,email'],
            'phone'          => ['required', 'string', 'max:25', 'regex:/^[+0-9()\-\s]+$/'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'username'       => [
                'required', 'string', 'min:3', 'max:50',
                'regex:/^[a-z0-9._-]+$/',
                'unique:lms_accounts,username',
            ],
            'password'       => [
                'required', 'string', 'min:8', 'max:128',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex'    => 'Phone number may only contain digits, spaces, +, -, or parentheses.',
            'username.regex' => 'Username may only contain lowercase letters, digits, dots, underscores or dashes.',
            'password.regex' => 'Password must include lowercase, uppercase, a digit, and a symbol.',
        ];
    }
}