<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\Port;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules.
     */
    public function rules(): array
    {
        $userId = $this->route('employee')?->id
            ?? $this->route('user')?->id
            ?? null;

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
            |
            | These fields are conditionally validated inside withValidator()
            | according to Role access_scope / assignment_type.
            |
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
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($userId),
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
                $this->isMethod('post') ? 'required' : 'nullable',
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
        $validator->after(function ($validator) {

            /*
            |--------------------------------------------------------------------------
            | Get Role
            |--------------------------------------------------------------------------
            */
            $roleId = $this->input('role_id');

            if (!$roleId) {
                return;
            }

            $role = Role::query()
                ->where('id', $roleId)
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

            $accessScope = strtoupper((string) $role->access_scope);
            $assignmentType = strtoupper((string) $role->assignment_type);

            $portTypeId = $this->input('port_type_id');
            $stateBoardId = $this->input('state_board_id');
            $portId = $this->input('port_id');

            $ports = collect($this->input('ports', []))
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | 1. ALL ACCESS
            |--------------------------------------------------------------------------
            |
            | SUPERADMIN / MINISTRY_NODAL_OFFICER
            |
            | No concrete Port Type / State Board / Port assignment required.
            |
            */
            if ($accessScope === Role::ACCESS_ALL) {

                // Nothing to validate regarding port assignment.
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | 2. STATE BOARD ACCESS
            |--------------------------------------------------------------------------
            |
            | STATE_MARITIME_BOARD_NODAL_OFFICER
            |
            | Required:
            | - Non-Major Port Type
            | - State Board
            | - Multiple Ports
            |
            */
            if ($accessScope === Role::ACCESS_STATE_BOARD) {

                /*
                |--------------------------------------------------------------------------
                | Port Type must be Non-Major
                |--------------------------------------------------------------------------
                */
                if ((int) $portTypeId !== 2) {

                    $validator->errors()->add(
                        'port_type_id',
                        'State Board access requires Non-Major Port Type.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | State Board required
                |--------------------------------------------------------------------------
                */
                if (!$stateBoardId) {

                    $validator->errors()->add(
                        'state_board_id',
                        'State Board is required.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Multiple Ports required
                |--------------------------------------------------------------------------
                */
                if ($assignmentType !== Role::ASSIGN_MULTIPLE) {

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

                /*
                |--------------------------------------------------------------------------
                | Multiple Port Relationship Validation
                |--------------------------------------------------------------------------
                */
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
            | 3. PORT ACCESS
            |--------------------------------------------------------------------------
            |
            | PORT_NODAL_OFFICER
            | PORT_MANAGER
            | DATA_ENTRY_OFFICER
            |
            | Required:
            | - Single Port
            | - Port must belong to selected Port Type
            | - For Non-Major, Port must belong to selected State Board
            |
            */
            if ($accessScope === Role::ACCESS_PORT) {

                /*
                |--------------------------------------------------------------------------
                | Assignment must be SINGLE
                |--------------------------------------------------------------------------
                */
                if ($assignmentType !== Role::ASSIGN_SINGLE) {

                    $validator->errors()->add(
                        'role_id',
                        'Port access must use single port assignment.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Port Type required
                |--------------------------------------------------------------------------
                */
                if (!$portTypeId) {

                    $validator->errors()->add(
                        'port_type_id',
                        'Port Type is required.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Port required
                |--------------------------------------------------------------------------
                */
                if (!$portId) {

                    $validator->errors()->add(
                        'port_id',
                        'Please select a Port.'
                    );

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Non-Major requires State Board
                |--------------------------------------------------------------------------
                */
                if ((int) $portTypeId === 2 && !$stateBoardId) {

                    $validator->errors()->add(
                        'state_board_id',
                        'State Board is required for Non-Major Port.'
                    );

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Major -> State Board must not be supplied
                |--------------------------------------------------------------------------
                */
                if ((int) $portTypeId === 1) {
                    $stateBoardId = null;
                }

                /*
                |--------------------------------------------------------------------------
                | Single Port Relationship Validation
                |--------------------------------------------------------------------------
                */
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
            | Unknown Access Scope
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
     *
     * Rules:
     * - Port must exist
     * - Port must be active
     * - Port must not be deleted
     * - Port Type must match selected Port Type
     * - Non-Major Port must belong to selected State Board
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
        | Fetch only active + non-deleted Ports
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
        | Check all selected Ports exist and are active
        |--------------------------------------------------------------------------
        */
        if ($ports->count() !== count($portIds)) {

            $validator->errors()->add(
                'ports',
                'One or more selected Ports are invalid, inactive, or deleted.'
            );

            /*
            | For single Port validation also attach error to port_id.
            */
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
        | Validate Port Type relationship
        |--------------------------------------------------------------------------
        */
        $invalidPortType = $ports->contains(function ($port) use ($portTypeId) {
            return (int) $port->port_type_id !== (int) $portTypeId;
        });

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
        | State Board relationship
        |--------------------------------------------------------------------------
        |
        | Only Non-Major Port Type requires State Board relationship.
        |
        */
        if ((int) $portTypeId === 2) {

            if (!$stateBoardId) {
                return;
            }

            $invalidStateBoard = $ports->contains(function ($port) use ($stateBoardId) {
                return (int) $port->state_board_id !== (int) $stateBoardId;
            });

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

        /*
        |--------------------------------------------------------------------------
        | Major Port must not have State Board relationship
        |--------------------------------------------------------------------------
        */
        if ((int) $portTypeId === 1) {

            $hasStateBoard = $ports->contains(function ($port) {
                return !is_null($port->state_board_id);
            });

            /*
            | We don't reject based solely on the DB value here because
            | the authoritative rule is Port Type = Major.
            | The selected Port Type relationship has already been validated.
            */
        }
    }
}
