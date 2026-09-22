<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\Port;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Is this a new employee creation request?
     */
    private function isCreateRequest(): bool
    {
        return $this->isMethod('post');
    }

    /**
     * Get employee from route model binding.
     */
    private function routeEmployee(): ?User
    {
        $employee = $this->route('employee');

        if ($employee instanceof User) {
            return $employee;
        }

        if (is_numeric($employee)) {
            return User::query()
                ->whereKey($employee)
                ->first();
        }

        return null;
    }

    /**
     * Is current logged-in user updating himself?
     */
    private function isSelfUpdate(): bool
    {
        if ($this->isCreateRequest()) {
            return false;
        }

        $employee = $this->routeEmployee();
        $user = auth()->user();

        return $employee
            && $user
            && (int) $employee->id === (int) $user->id;
    }

    /**
     * Is current user SUPERADMIN?
     */
    private function isSuperAdmin(): bool
    {
        $user = auth()->user();

        return $user
            && strtoupper(
                trim(
                    (string) optional($user->role)->role_code
                )
            ) === 'SUPERADMIN';
    }

    /**
     * Validation Rules.
     */
    public function rules(): array
    {
        /*
        |--------------------------------------------------------------------------
        | SELF UPDATE
        |--------------------------------------------------------------------------
        |
        | Non-SUPERADMIN can update only personal/profile information.
        |
        */
        if ($this->isSelfUpdate() && !$this->isSuperAdmin()) {

            return [
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
                    'nullable',
                    'string',
                    'max:100',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')
                        ->ignore($this->routeEmployee()?->id),
                ],

                'username' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('users', 'username')
                        ->ignore($this->routeEmployee()?->id),
                ],

                'mobile_number' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'official_address' => [
                    'nullable',
                    'string',
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE / SUPERADMIN UPDATE
        |--------------------------------------------------------------------------
        */

        $userId = $this->routeEmployee()?->id;

        return [
            /*
            |--------------------------------------------------------------------------
            | Employee Information
            |--------------------------------------------------------------------------
            */

            'employee_code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'employee_code')
                    ->ignore($userId),
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
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Organization
            |--------------------------------------------------------------------------
            */

            'organization_id' => [
                'required',
                'integer',
                'exists:organizations,id',
            ],

            'department_id' => [
                'required',
                'integer',
                'exists:departments,id',
            ],

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'state_id' => [
                'required',
                'integer',
                'exists:states,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Port Assignment
            |--------------------------------------------------------------------------
            */

            'port_type_id' => [
                'nullable',
                'integer',
                'exists:port_categories,id',
            ],

            'state_board_id' => [
                'nullable',
                'integer',
                'exists:state_boards,id',
            ],

            'port_id' => [
                'nullable',
                'integer',
                'exists:ports,id',
            ],

            'ports' => [
                'nullable',
                'array',
            ],

            'ports.*' => [
                'integer',
                'distinct',
                'exists:ports,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Other Information
            |--------------------------------------------------------------------------
            */

            'report_to_user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($userId),
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')
                    ->ignore($userId),
            ],

            'mobile_number' => [
                'nullable',
                'string',
                'max:20',
            ],

            'official_address' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */

            'password' => [
                $this->isCreateRequest()
                    ? 'required'
                    : 'nullable',

                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ];
    }

    /**
     * Configure validator.
     */
    public function withValidator($validator): void
    {
        /*
        |--------------------------------------------------------------------------
        | Self Update
        |--------------------------------------------------------------------------
        |
        | No role/port/status/assignment validation.
        |
        */
        if (
            $this->isSelfUpdate()
            && !$this->isSuperAdmin()
        ) {
            return;
        }

        $validator->after(function ($validator) {

            $roleId = $this->input('role_id');

            if (!$roleId) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Validate Role
            |--------------------------------------------------------------------------
            */

            $role = Role::query()
                ->whereKey($roleId)
                ->where('status', true)
                ->where('is_deleted', false)
                ->first();

            if (!$role) {

                $validator->errors()->add(
                    'role_id',
                    'Selected role is invalid or inactive.'
                );

                return;
            }

            $accessScope = strtoupper(
                trim((string) $role->access_scope)
            );

            $assignmentType = strtoupper(
                trim((string) $role->assignment_type)
            );

            $portTypeId = $this->input('port_type_id');
            $stateBoardId = $this->input('state_board_id');
            $portId = $this->input('port_id');

            $ports = collect(
                $this->input('ports', [])
            )
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | ALL ACCESS
            |--------------------------------------------------------------------------
            */

            if ($accessScope === Role::ACCESS_ALL) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | STATE BOARD ACCESS
            |--------------------------------------------------------------------------
            */

            if ($accessScope === Role::ACCESS_STATE_BOARD) {

                if ((int) $portTypeId !== 2) {

                    $validator->errors()->add(
                        'port_type_id',
                        'State Board access requires Non-Major Port Type.'
                    );
                }

                if (!$stateBoardId) {

                    $validator->errors()->add(
                        'state_board_id',
                        'State Board is required.'
                    );
                }

                if (
                    $assignmentType
                    !== Role::ASSIGN_MULTIPLE
                ) {

                    $validator->errors()->add(
                        'role_id',
                        'State Board access must use multiple port assignment.'
                    );
                }

                if (empty($ports)) {

                    $validator->errors()->add(
                        'ports',
                        'Please select at least one Port.'
                    );

                    return;
                }

                $this->validatePortRelationships(
                    validator: $validator,
                    portIds: $ports,
                    portTypeId: $portTypeId,
                    stateBoardId: $stateBoardId
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | PORT ACCESS
            |--------------------------------------------------------------------------
            */

            if ($accessScope === Role::ACCESS_PORT) {

                if (
                    $assignmentType
                    !== Role::ASSIGN_SINGLE
                ) {

                    $validator->errors()->add(
                        'role_id',
                        'Port access must use single port assignment.'
                    );
                }

                if (!$portTypeId) {

                    $validator->errors()->add(
                        'port_type_id',
                        'Port Type is required.'
                    );
                }

                if (!$portId) {

                    $validator->errors()->add(
                        'port_id',
                        'Please select a Port.'
                    );

                    return;
                }

                if (
                    (int) $portTypeId === 2
                    && !$stateBoardId
                ) {

                    $validator->errors()->add(
                        'state_board_id',
                        'State Board is required for Non-Major Port.'
                    );

                    return;
                }

                $this->validatePortRelationships(
                    validator: $validator,
                    portIds: [$portId],
                    portTypeId: $portTypeId,
                    stateBoardId: $stateBoardId
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Invalid Scope
            |--------------------------------------------------------------------------
            */

            $validator->errors()->add(
                'role_id',
                'The selected role has an invalid access scope.'
            );
        });
    }

    /**
     * Validate Port relationships.
     */
    private function validatePortRelationships(
        $validator,
        array $portIds,
        $portTypeId,
        $stateBoardId = null
    ): void {

        $portIds = collect($portIds)
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($portIds)) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Fetch active Ports
        |--------------------------------------------------------------------------
        */

        $ports = Port::query()
            ->whereIn('id', $portIds)
            ->where('status', true)
            ->where('is_deleted', false)
            ->get([
                'id',
                'port_type_id',
                'state_board_id',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Validate existence/status
        |--------------------------------------------------------------------------
        */

        if ($ports->count() !== count($portIds)) {

            $validator->errors()->add(
                'ports',
                'One or more selected Ports are invalid, inactive, or deleted.'
            );

            if (count($portIds) === 1) {

                $validator->errors()->add(
                    'port_id',
                    'Selected Port is invalid, inactive, or deleted.'
                );
            }

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Port Type
        |--------------------------------------------------------------------------
        */

        $invalidPortType = $ports->contains(
            function ($port) use ($portTypeId) {

                return (int) $port->port_type_id
                    !== (int) $portTypeId;
            }
        );

        if ($invalidPortType) {

            $validator->errors()->add(
                'port_type_id',
                'Selected Port does not belong to the selected Port Type.'
            );

            if (count($portIds) === 1) {

                $validator->errors()->add(
                    'port_id',
                    'Selected Port does not belong to the selected Port Type.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate State Board
        |--------------------------------------------------------------------------
        */

        if ((int) $portTypeId === 2) {

            if (!$stateBoardId) {
                return;
            }

            $invalidStateBoard = $ports->contains(
                function ($port) use ($stateBoardId) {

                    return (int) $port->state_board_id
                        !== (int) $stateBoardId;
                }
            );

            if ($invalidStateBoard) {

                $validator->errors()->add(
                    'state_board_id',
                    'One or more selected Ports do not belong to the selected State Board.'
                );

                if (count($portIds) === 1) {

                    $validator->errors()->add(
                        'port_id',
                        'Selected Port does not belong to the selected State Board.'
                    );
                }
            }
        }
    }
}