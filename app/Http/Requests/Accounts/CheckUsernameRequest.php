<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class CheckUsernameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'           => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/'],
            'exclude_account_id' => ['nullable', 'integer'],
        ];
    }
}