<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Exceptions\EmployeeException;
use App\Exceptions\MasterDataException;
use App\Services\MasterDataService;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class EmployeeService
{
    /*
    |--------------------------------------------------------------------------
    | Fields allowed during self update
    |--------------------------------------------------------------------------
    |
    | Self user can update profile information only.
    | Role / Port / Status / Access assignment cannot be changed.
    |
    */

    private const SELF_UPDATE_FIELDS = [
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'mobile_number',
        'username',
        'official_address'
    ];

    protected MasterDataService $masterDataService;

    public function __construct(MasterDataService $masterDataService)
    {
        $this->masterDataService = $masterDataService;
    }

    private function executeEmployeeQuery(Closure $callback, string $message): mixed
    {
        try {
            return $callback();
        } catch (EmployeeException $e) {
            throw $e;
        } catch (MasterDataException $e) {
            throw new EmployeeException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            report($e);
            throw new EmployeeException($message, previous: $e);
        }
    }

    public function getEmployees(array $filters = []): LengthAwarePaginator
    {
        return $this->executeEmployeeQuery(function () use ($filters) {

            $query = User::query()
                ->where('is_deleted', false)
                ->with(self::RELATIONS);

            /*
        |--------------------------------------------------------------------------
        | Employee Access Scope
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Access restriction is applied at DATABASE QUERY level.
        |
        */
            $this->applyAccessScope(
                query: $query,
                user: auth()->user()
            );

            /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

            if (!empty($filters['search'])) {

                $search = trim($filters['search']);

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'employee_code',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'first_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'middle_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'last_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'username',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'mobile_number',
                            'like',
                            "%{$search}%"
                        );
                });
            }

            /*
        |--------------------------------------------------------------------------
        | Normal Filters
        |--------------------------------------------------------------------------
        */

            foreach (
                [
                    'organization_id',
                    'department_id',
                    'role_id',
                    'state_id',
                    'port_type_id',
                    'state_board_id',
                    'port_id',
                ] as $field
            ) {

                if (!empty($filters[$field])) {

                    $query->where(
                        $field,
                        $filters[$field]
                    );
                }
            }

            /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

            if (
                array_key_exists('status', $filters) &&
                $filters['status'] !== ''
            ) {

                $query->where(
                    'status',
                    $filters['status']
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

            $allowed = [
                'id',
                'employee_code',
                'name',
                'email',
                'created_at',
            ];

            $sortBy = in_array(
                $filters['sort_by'] ?? 'id',
                $allowed,
                true
            )
                ? ($filters['sort_by'] ?? 'id')
                : 'id';

            $sortOrder =
                strtolower(
                    $filters['sort_order'] ?? 'desc'
                ) === 'asc'
                ? 'asc'
                : 'desc';

            /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

            return $query
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10)
                ->withQueryString();
        }, 'Unable to load employees.');
    }

    /**
     * Apply logged-in user's employee access scope.
     *
     * IMPORTANT:
     * This method restricts the database query itself.
     */
    private function applyAccessScope(
        Builder $query,
        ?User $user
    ): void {

        /*
    |--------------------------------------------------------------------------
    | No authenticated user
    |--------------------------------------------------------------------------
    */

        if (!$user) {

            $query->whereRaw('1 = 0');

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Load Role
    |--------------------------------------------------------------------------
    */

        $user->loadMissing('role');

        $role = $user->role;

        /*
    |--------------------------------------------------------------------------
    | No Role
    |--------------------------------------------------------------------------
    */

        if (!$role) {

            $query->whereRaw('1 = 0');

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Inactive / Deleted Role
    |--------------------------------------------------------------------------
    */

        if (
            !$role->status ||
            $role->is_deleted
        ) {

            $query->whereRaw('1 = 0');

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Access Scope
    |--------------------------------------------------------------------------
    */

        $accessScope = strtoupper(
            trim((string) $role->access_scope)
        );

        /*
    |--------------------------------------------------------------------------
    | ALL
    |--------------------------------------------------------------------------
    |
    | SUPERADMIN
    | MINISTRY_NODAL_OFFICER
    | NIC
    |
    */

        if ($accessScope === Role::ACCESS_ALL) {

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | STATE BOARD
    |--------------------------------------------------------------------------
    |
    | User must have:
    |
    | 1. state_board_id
    | 2. assigned ports
    |
    | Employee must belong to:
    |
    | 1. same state board
    | 2. one of the user's assigned ports
    |
    */

        if ($accessScope === Role::ACCESS_STATE_BOARD) {

            /*
            |--------------------------------------------------------------------------
            | State Board Access
            |--------------------------------------------------------------------------
            |
            | User can see:
            |
            | 1. Own employee record
            | 2. Employees belonging to same State Board
            |    AND assigned ports
            |
            */

            $query->where(function (Builder $scopeQuery) use ($user) {

                /*
                |--------------------------------------------------------------------------
                | Own Employee Record
                |--------------------------------------------------------------------------
                |
                | State Board Nodal Officer does not have users.port_id
                | because assignment is stored in user_ports.
                |
                */
                $scopeQuery->whereKey($user->id);

                /*
                |--------------------------------------------------------------------------
                | Assigned Employees
                |--------------------------------------------------------------------------
                */
                if (!empty($user->state_board_id)) {

                    $scopeQuery->orWhere(function (Builder $assignedQuery) use ($user) {

                        $assignedQuery
                            ->where(
                                'state_board_id',
                                $user->state_board_id
                            )
                            ->whereIn(
                                'port_id',
                                function ($subQuery) use ($user) {

                                    $subQuery
                                        ->select('port_id')
                                        ->from('user_ports')
                                        ->where(
                                            'user_id',
                                            $user->id
                                        )
                                        ->where(
                                            'status',
                                            true
                                        )
                                        ->where(
                                            'is_deleted',
                                            false
                                        );
                                }
                            );
                    });
                }
            });

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | PORT
    |--------------------------------------------------------------------------
    |
    | PORT roles use users.port_id.
    |
    | PORT_NODAL_OFFICER
    | PORT_MANAGER
    | DATA_ENTRY_OFFICER
    |
    */

        if ($accessScope === Role::ACCESS_PORT) {

            if (empty($user->port_id)) {

                $query->whereRaw('1 = 0');

                return;
            }

            $portId = (int) $user->port_id;

            $query->where(function (Builder $scopeQuery) use ($portId, $user) {

                /*
        |--------------------------------------------------------------------------
        | Employees having direct SINGLE port assignment
        |--------------------------------------------------------------------------
        */
                $scopeQuery->where(
                    'port_id',
                    $portId
                );

                /*
        |--------------------------------------------------------------------------
        | Employees having MULTIPLE port assignment
        |--------------------------------------------------------------------------
        |
        | Example:
        | STATE_MARITIME_BOARD_NODAL_OFFICER
        |
        | Their port_id is NULL and actual assignments
        | are stored in user_ports.
        |
        */
                $scopeQuery->orWhereIn(
                    'id',
                    function ($subQuery) use ($portId) {

                        $subQuery
                            ->select('user_id')
                            ->from('user_ports')
                            ->where(
                                'port_id',
                                $portId
                            )
                            ->where(
                                'status',
                                true
                            )
                            ->where(
                                'is_deleted',
                                false
                            );
                    }
                );
            });

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | CUSTOM
    |--------------------------------------------------------------------------
    |
    | Custom access rules are not implemented yet.
    |
    */

        if ($accessScope === Role::ACCESS_CUSTOM) {

            $query->whereRaw('1 = 0');

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Unknown Access Scope
    |--------------------------------------------------------------------------
    */

        $query->whereRaw('1 = 0');
    }

    public function getDropdownData(): array
    {
        return $this->executeEmployeeQuery(fn() => [
            'organizations' => $this->masterDataService->getOrganizations(),
            'departments' => $this->masterDataService->getDepartments(),
            'roles' => $this->masterDataService->getRoles(),
            'states' => $this->masterDataService->getStates(),
            'portCategories' => $this->masterDataService->getPortCategories(),
            'stateBoards' => collect(),
            'ports' => collect(),
            'reportingOfficers' => $this->masterDataService->getReportingOfficers(),
        ], 'Unable to load dropdown data.');
    }


    /*
    |--------------------------------------------------------------------------
    | Existing relations
    |--------------------------------------------------------------------------
    */
    private const RELATIONS = [
        'organization',
        'department',
        'role',
        'state',
        'stateBoard',
        'port',
        'assignedPorts',
        'reportingOfficer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Create Employee
    |--------------------------------------------------------------------------
    */
    public function createEmployee(array $data): User
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Extract Multiple Port IDs
            |--------------------------------------------------------------------------
            */
            $assignedPorts = collect($data['ports'] ?? [])
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            unset($data['ports']);

            /*
            |--------------------------------------------------------------------------
            | Generate Employee Code
            |--------------------------------------------------------------------------
            */
            if (empty($data['employee_code'])) {
                $data['employee_code'] = $this->generateEmployeeCode();
            }

            /*
            |--------------------------------------------------------------------------
            | Build Full Name
            |--------------------------------------------------------------------------
            */
            $data['name'] = collect([
                $data['first_name'] ?? null,
                $data['middle_name'] ?? null,
                $data['last_name'] ?? null,
            ])
                ->filter()
                ->implode(' ');

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            unset($data['password_confirmation']);

            /*
            |--------------------------------------------------------------------------
            | Defaults
            |--------------------------------------------------------------------------
            */
            $data['status'] = $data['status'] ?? true;
            $data['force_password_change'] = true;
            $data['is_deleted'] = false;
            $data['created_by'] = auth()->id();

            /*
            |--------------------------------------------------------------------------
            | Get Role
            |--------------------------------------------------------------------------
            */
            $role = Role::query()
                ->whereKey($data['role_id'])
                ->where('status', true)
                ->where('is_deleted', false)
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Normalize Assignment
            |--------------------------------------------------------------------------
            |
            | ALL      -> no concrete port assignment
            | MULTIPLE -> user_ports
            | SINGLE   -> users.port_id
            |
            */
            $this->normalizeAssignmentData(
                data: $data,
                role: $role,
                assignedPorts: $assignedPorts
            );

            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            */
            $employee = User::create($data);

            /*
            |--------------------------------------------------------------------------
            | Save Port Assignment
            |--------------------------------------------------------------------------
            */
            $this->syncAssignments(
                user: $employee,
                role: $role,
                assignedPorts: $assignedPorts
            );

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Employee
            |--------------------------------------------------------------------------
            */
            return $employee->fresh(self::RELATIONS);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Update Employee
    |--------------------------------------------------------------------------
    */
    public function updateEmployee(User $employee, array $data): User
    {
        return DB::transaction(function () use ($employee, $data) {

            /*
            |--------------------------------------------------------------------------
            | Self Update Protection
            |--------------------------------------------------------------------------
            */

            $currentUser = auth()->user();

            /*
        |--------------------------------------------------------------------------
        | Determine SUPERADMIN
        |--------------------------------------------------------------------------
        */

            $isSuperAdmin =
                $currentUser &&
                strtoupper(
                    trim((string) optional($currentUser->role)->role_code)
                ) === 'SUPERADMIN';


            /*
        |--------------------------------------------------------------------------
        | Determine Self
        |--------------------------------------------------------------------------
        */

            $isSelf =
                $currentUser &&
                (int) $currentUser->id === (int) $employee->id;


            /*
            |--------------------------------------------------------------------------
            | Self Update Protection
            |--------------------------------------------------------------------------
            |
            | Only profile fields can be changed.
            |
            */

            if ($isSelf && !$isSuperAdmin) {

                $data = array_intersect_key(
                    $data,
                    array_flip(self::SELF_UPDATE_FIELDS)
                );

                /*
            | Never allow these fields from self update.
            */
                unset(
                    $data['role_id'],
                    $data['port_id'],
                    $data['state_board_id'],
                    $data['ports'],
                    $data['status'],
                    $data['is_deleted'],
                    $data['force_password_change'],
                    $data['created_by'],
                    $data['updated_by']
                );

                /*
            | Password is handled separately.
            */
                unset(
                    $data['password'],
                    $data['password_confirmation']
                );

                /*
            | Audit
            */
                $data['updated_by'] = $currentUser->id;

                /*
            | Build name
            */
                $data['name'] = collect([
                    $data['first_name'] ?? $employee->first_name,
                    $data['middle_name'] ?? $employee->middle_name,
                    $data['last_name'] ?? $employee->last_name,
                ])
                    ->filter()
                    ->implode(' ');

                /*
            | Update only profile fields.
            */
                $employee->update($data);

                return $employee->fresh(self::RELATIONS);
            }

            /*
            |--------------------------------------------------------------------------
            | Extract Multiple Port IDs
            |--------------------------------------------------------------------------
            */
            $assignedPorts = collect($data['ports'] ?? [])
                ->filter(fn($id) => filled($id))
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            unset($data['ports']);

            /*
            |--------------------------------------------------------------------------
            | Build Full Name
            |--------------------------------------------------------------------------
            */
            $data['name'] = collect([
                $data['first_name'] ?? $employee->first_name,
                $data['middle_name'] ?? $employee->middle_name,
                $data['last_name'] ?? $employee->last_name,
            ])
                ->filter()
                ->implode(' ');

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            |
            | Password is handled separately.
            |
            */
            unset(
                $data['password'],
                $data['password_confirmation']
            );

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */
            $data['updated_by'] = auth()->id();

            /*
            |--------------------------------------------------------------------------
            | Get New Role
            |--------------------------------------------------------------------------
            */
            $role = Role::query()
                ->whereKey($data['role_id'])
                ->where('status', true)
                ->where('is_deleted', false)
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Normalize Assignment BEFORE update
            |--------------------------------------------------------------------------
            */
            $this->normalizeAssignmentData(
                data: $data,
                role: $role,
                assignedPorts: $assignedPorts
            );

            /*
            |--------------------------------------------------------------------------
            | Update User
            |--------------------------------------------------------------------------
            */
            $employee->update($data);

            /*
            |--------------------------------------------------------------------------
            | Clean + Save Assignments
            |--------------------------------------------------------------------------
            |
            | syncAssignments() handles role changes as well.
            |
            */
            $this->syncAssignments(
                user: $employee->fresh(),
                role: $role,
                assignedPorts: $assignedPorts
            );

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Employee
            |--------------------------------------------------------------------------
            */
            return $employee->fresh(self::RELATIONS);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Assignment Data
    |--------------------------------------------------------------------------
    |
    | This ensures users.port_id contains a value ONLY for SINGLE access.
    |
    */
    private function normalizeAssignmentData(
        array &$data,
        Role $role,
        array $assignedPorts
    ): void {

        $accessScope = strtoupper((string) $role->access_scope);
        $assignmentType = strtoupper((string) $role->assignment_type);

        /*
        |--------------------------------------------------------------------------
        | ALL ACCESS
        |--------------------------------------------------------------------------
        |
        | SUPERADMIN
        | MINISTRY_NODAL_OFFICER
        |
        | No concrete port assignment.
        |
        */
        if ($accessScope === Role::ACCESS_ALL) {

            $data['port_id'] = null;

            /*
            | State Board is also not an assignment for ALL access.
            */
            $data['state_board_id'] = null;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | MULTIPLE PORT ASSIGNMENT
        |--------------------------------------------------------------------------
        |
        | State Board Nodal Officer
        |
        */
        if ($assignmentType === Role::ASSIGN_MULTIPLE) {

            /*
            | users.port_id must remain NULL.
            */
            $data['port_id'] = null;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SINGLE PORT ASSIGNMENT
        |--------------------------------------------------------------------------
        |
        | Port Nodal Officer
        | Port Manager
        | Data Entry Officer
        |
        */
        if ($assignmentType === Role::ASSIGN_SINGLE) {

            /*
            | Single port comes from port_id.
            */
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Unknown assignment type
        |--------------------------------------------------------------------------
        */
        throw new \InvalidArgumentException(
            "Invalid assignment type [{$role->assignment_type}] for role [{$role->role_code}]."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sync Port Assignments
    |--------------------------------------------------------------------------
    |
    | This is the important method for handling:
    |
    | ALL       -> detach everything
    | MULTIPLE  -> sync user_ports
    | SINGLE    -> detach user_ports
    |
    */
    private function syncAssignments(
        User $user,
        Role $role,
        array $assignedPorts
    ): void {

        $accessScope = strtoupper((string) $role->access_scope);
        $assignmentType = strtoupper((string) $role->assignment_type);

        /*
        |--------------------------------------------------------------------------
        | ALL ACCESS
        |--------------------------------------------------------------------------
        |
        | No user_ports records.
        | No users.port_id.
        |
        */
        if ($accessScope === Role::ACCESS_ALL) {

            $user->assignedPorts()->detach();

            $user->update([
                'port_id' => null,
                'state_board_id' => null,
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | MULTIPLE PORTS
        |--------------------------------------------------------------------------
        */
        if ($assignmentType === Role::ASSIGN_MULTIPLE) {

            /*
            | users.port_id must not contain old SINGLE assignment.
            */
            $user->update([
                'port_id' => null,
            ]);

            /*
            | Replace old assignments with current selected ports.
            */
            $user->assignedPorts()->sync($assignedPorts);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SINGLE PORT
        |--------------------------------------------------------------------------
        */
        if ($assignmentType === Role::ASSIGN_SINGLE) {

            /*
            | Remove any old MULTIPLE assignments.
            */
            $user->assignedPorts()->detach();

            /*
            | users.port_id is authoritative.
            |
            | port_id was already validated by EmployeeRequest.
            */
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Safety
        |--------------------------------------------------------------------------
        */
        throw new \InvalidArgumentException(
            "Unable to sync assignment for role [{$role->role_code}]."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Employee Code
    |--------------------------------------------------------------------------
    */
    public function deleteEmployee(User $user): bool
    {
        return $this->executeEmployeeQuery(fn() => DB::transaction(function () use ($user) {
            $user->assignedPorts()->detach();
            return $user->update([
                'status' => false,
                'is_deleted' => true,
                'updated_by' => auth()->id(),
            ]);
        }), 'Unable to delete employee.');
    }

    public function changeStatus(User $user): User
    {
        return $this->executeEmployeeQuery(fn() => DB::transaction(function () use ($user) {
            $user->update(['status' => !$user->status, 'updated_by' => auth()->id()]);
            return $user->fresh(self::RELATIONS);
        }), 'Unable to update employee status.');
    }

    private function generateEmployeeCode(): string
    {
        return $this->executeEmployeeQuery(function () {
            $lastEmployee = User::query()->lockForUpdate()->latest('id')->first();
            if (!$lastEmployee || empty($lastEmployee->employee_code)) return 'EMP000001';
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastEmployee->employee_code);
            return 'EMP' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }, 'Unable to generate employee code.');
    }
}
