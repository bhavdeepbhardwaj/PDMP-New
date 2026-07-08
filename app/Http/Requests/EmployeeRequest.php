<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $userId = $this->route('employee')?->id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'employee_code' => [
                // 'required',
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'employee_code')->ignore($userId),
            ],

            'title' => [
                'required',
                'string',
                'max:20',
            ],

            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'middle_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contact Information
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'mobile_number' => [
                'required',
                'digits_between:10,15',
            ],

            'official_address' => [
                'nullable',
                'string',
                'max:500',
            ],

            /*
            |--------------------------------------------------------------------------
            | Employee Details
            |--------------------------------------------------------------------------
            */

            'organization_id' => [
                'required',
                'exists:organizations,id',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'state_id' => [
                'required',
                'exists:states,id',
            ],

            'state_board_id' => [
                'nullable',
                // 'required',
                'exists:state_boards,id',
            ],

            'port_id' => [
                'nullable',
                // 'required',
                'exists:ports,id',
            ],

            'ports' => [
                'nullable',
                'array',
            ],

            'ports.*' => [
                'exists:ports,id',
            ],

            'port_type_id' => [
                'required',
                'string',
                'max:50',
            ],

            'report_to_user_id' => [
                'required',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */

            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'employee_code.required' => 'Employee Code is required.',
            'employee_code.unique'   => 'Employee Code already exists.',

            'first_name.required'    => 'First Name is required.',
            'last_name.required'     => 'Last Name is required.',

            'email.required'         => 'Email is required.',
            'email.email'            => 'Enter a valid email address.',
            'email.unique'           => 'Email already exists.',

            'organization_id.required' => 'Please select Organization.',
            'organization_id.exists'   => 'Invalid Organization selected.',

            'department_id.required' => 'Please select Department.',
            'department_id.exists'   => 'Invalid Department selected.',

            'role_id.required' => 'Please select Role.',
            'role_id.exists'   => 'Invalid Role selected.',

            'password.required'  => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.regex'     => 'Password must contain uppercase, lowercase, number and special character.',

            'state_id.required' => 'Please select States.',
            'state_id.exists'   => 'Invalid States selected.',

            'state_board_id.required' => 'Please select States Doard.',
            'state_board_id.exists'   => 'Invalid States Doard selected.',

            'port_id.required' => 'Please select Port.',
            'port_id.exists'   => 'Invalid Port selected.',

            'port_type_id.required' => 'Please select Port Type.',
            'port_type_id.exists'   => 'Invalid Port Type selected.',

            'mobile_number.required' => 'Mobile Number is required.',
            'mobile_number.exists'   => 'Invalid Mobile Number selected.',

            'report_to_user_id.required' => 'Please select Report To User.',
            'report_to_user_id.exists'   => 'Invalid Report To User selected.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $role = Role::find($this->role_id);

            if (!$role) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | State Board Validation
            |--------------------------------------------------------------------------
            */

            if (
                $this->port_type_id == 2 &&
                empty($this->state_board_id)
            ) {

                $validator->errors()->add(
                    'state_board_id',
                    'Please select State Board.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Multiple Port Role
            |--------------------------------------------------------------------------
            */

            if ($role->assignment_type === 'MULTIPLE') {

                if (
                    empty($this->ports) ||
                    count($this->ports) === 0
                ) {

                    $validator->errors()->add(
                        'ports',
                        'Please select at least one Port.'
                    );
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Single Port Role
            |--------------------------------------------------------------------------
            */

            if (empty($this->port_id)) {

                $validator->errors()->add(
                    'port_id',
                    'Please select Port.'
                );
            }
        });
    }
}
