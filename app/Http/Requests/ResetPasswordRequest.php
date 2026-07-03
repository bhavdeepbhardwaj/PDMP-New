<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Reset Token
            |--------------------------------------------------------------------------
            */

            'token' => [
                'required',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Email
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],

            /*
            |--------------------------------------------------------------------------
            | New Password
            |--------------------------------------------------------------------------
            */

            'password' => [

                'required',

                'confirmed',

                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),

            ],

        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            'token.required' => 'Reset token is missing.',

            'email.required' => 'Email address is required.',

            'email.email' => 'Please enter a valid email address.',

            'email.exists' => 'No account found with this email address.',

            'password.required' => 'New password is required.',

            'password.confirmed' => 'Password confirmation does not match.',

        ];
    }

    /**
     * Sanitize Input
     */
    protected function prepareForValidation(): void
    {
        $this->merge([

            'email' => strtolower(trim((string) $this->email)),

        ]);
    }
}
