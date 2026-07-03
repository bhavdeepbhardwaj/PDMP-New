<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'employee_code' => ['required', 'string'],
            'password'      => ['required', 'string'],
            'remember'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_code.required' => 'Employee Code is required.',
            'password.required'      => 'Password is required.',
        ];
    }
}
