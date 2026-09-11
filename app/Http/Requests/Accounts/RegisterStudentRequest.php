<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'  => ['required', 'string', 'max:50', 'regex:/^[\p{L}\s.\'-]+$/u'],
            'middle_name' => ['nullable', 'string', 'max:50', 'regex:/^[\p{L}\s.\'-]+$/u'],
            'last_name'   => ['required', 'string', 'max:50', 'regex:/^[\p{L}\s.\'-]+$/u'],
            'email'       => ['required', 'email:rfc', 'max:100', 'unique:students,email'],
            'phone'       => ['required', 'string', 'max:25', 'regex:/^[+0-9()\-\s]+$/'],
            'gender'      => ['required', 'in:Male,Female,Other'],
            'birthdate'   => ['required', 'date_format:Y-m-d', 'before:-10 years', 'after:-100 years'],
            'address'     => ['required', 'string', 'min:5', 'max:255'],
            'grade_level' => ['required', 'in:11,12'],
            'username'    => [
                'required', 'string', 'min:3', 'max:50',
                'regex:/^[a-z0-9._-]+$/',
                'unique:lms_accounts,username',
            ],
            'password'    => [
                'required', 'string', 'min:8', 'max:128',
                'regex:/[a-z]/',        // lowercase
                'regex:/[A-Z]/',        // uppercase
                'regex:/[0-9]/',        // digit
                'regex:/[^A-Za-z0-9]/', // symbol
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex'      => 'Phone number may only contain digits, spaces, +, -, or parentheses.',
            'birthdate.before' => 'You must be at least 10 years old to register.',
            'username.regex'   => 'Username may only contain lowercase letters, digits, dots, underscores or dashes.',
            'password.regex'   => 'Password must include lowercase, uppercase, a digit, and a symbol.',
        ];
    }
}