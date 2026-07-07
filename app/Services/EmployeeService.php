<?php

namespace App\Services;

use App\Exceptions\EmployeeException;
use App\Exceptions\MasterDataException;
use App\Models\User;
use App\Services\MasterDataService;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeService
{
    /**
     * Master Data Service Instance.
     */
    protected MasterDataService $masterDataService;

    /**
     * Employee Relationships.
     */
    private const RELATIONS = [

        'organization',

        'department',

        'role',

        'state',

        'stateBoard',

        'port',

        'reportingOfficer',

    ];

    /**
     * Create a new service instance.
     */
    public function __construct(
        MasterDataService $masterDataService
    ) {
        $this->masterDataService = $masterDataService;
    }

    /**
     * Execute Employee Query.
     *
     * Wraps all employee service operations with
     * centralized exception handling.
     *
     * @throws EmployeeException
     */
    private function executeEmployeeQuery(
        Closure $callback,
        string $message
    ): mixed {

        try {

            return $callback();
        } catch (EmployeeException $e) {

            throw $e;
        } catch (MasterDataException $e) {

            throw new EmployeeException(
                $e->getMessage(),
                previous: $e
            );
        } catch (\Throwable $e) {

            report($e);

            throw new EmployeeException(
                $message,
                previous: $e
            );
        }
    }

    /**
     * Get Employees.
     *
     * Search + Filter + Sorting + Pagination.
     *
     * @throws EmployeeException
     */
    public function getEmployees(
        array $filters = []
    ): LengthAwarePaginator {

        return $this->executeEmployeeQuery(

            callback: function () use ($filters) {

                $query = User::query()

                    ->where('is_deleted', false)

                    ->with(self::RELATIONS);

                /*
                |--------------------------------------------------------------------------
                | Search
                |--------------------------------------------------------------------------
                */

                if (!empty($filters['search'])) {

                    $search = trim($filters['search']);

                    $query->where(function ($query) use ($search) {

                        $query

                            ->where('employee_code', 'like', "%{$search}%")

                            ->orWhere('name', 'like', "%{$search}%")

                            ->orWhere('first_name', 'like', "%{$search}%")

                            ->orWhere('middle_name', 'like', "%{$search}%")

                            ->orWhere('last_name', 'like', "%{$search}%")

                            ->orWhere('email', 'like', "%{$search}%")

                            ->orWhere('username', 'like', "%{$search}%")

                            ->orWhere('mobile_number', 'like', "%{$search}%");
                    });
                }

                /*
                |--------------------------------------------------------------------------
                | Filters
                |--------------------------------------------------------------------------
                */

                $query

                    ->when(
                        !empty($filters['organization_id']),
                        fn($q) => $q->where(
                            'organization_id',
                            $filters['organization_id']
                        )
                    )

                    ->when(
                        !empty($filters['department_id']),
                        fn($q) => $q->where(
                            'department_id',
                            $filters['department_id']
                        )
                    )

                    ->when(
                        !empty($filters['role_id']),
                        fn($q) => $q->where(
                            'role_id',
                            $filters['role_id']
                        )
                    )

                    ->when(
                        !empty($filters['state_id']),
                        fn($q) => $q->where(
                            'state_id',
                            $filters['state_id']
                        )
                    )

                    ->when(
                        !empty($filters['port_type_id']),
                        fn($q) => $q->where(
                            'port_type_id',
                            $filters['port_type_id']
                        )
                    )

                    ->when(
                        !empty($filters['state_board_id']),
                        fn($q) => $q->where(
                            'state_board_id',
                            $filters['state_board_id']
                        )
                    )

                    ->when(
                        !empty($filters['port_id']),
                        fn($q) => $q->where(
                            'port_id',
                            $filters['port_id']
                        )
                    );

                if (
                    array_key_exists('status', $filters)
                    && $filters['status'] !== ''
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

                $allowedSorts = [

                    'id',

                    'employee_code',

                    'name',

                    'email',

                    'created_at',

                ];

                $sortBy = $filters['sort_by'] ?? 'id';

                if (!in_array($sortBy, $allowedSorts, true)) {

                    $sortBy = 'id';
                }

                $sortOrder = strtolower(
                    $filters['sort_order'] ?? 'desc'
                );

                $sortOrder = $sortOrder === 'asc'
                    ? 'asc'
                    : 'desc';

                $query->orderBy(
                    $sortBy,
                    $sortOrder
                );

                /*
                |--------------------------------------------------------------------------
                | Pagination
                |--------------------------------------------------------------------------
                */

                return $query

                    ->paginate(10)

                    ->withQueryString();
            },

            message: 'Unable to load employees.'

        );
    }

    /**
     * Get Employee Dropdown Data.
     *
     * @throws EmployeeException
     */
    public function getDropdownData(): array
    {
        return $this->executeEmployeeQuery(

            callback: fn() => [

                'organizations' => $this->masterDataService
                    ->getOrganizations(),

                'departments' => $this->masterDataService
                    ->getDepartments(),

                'roles' => $this->masterDataService
                    ->getRoles(),

                'states' => $this->masterDataService
                    ->getStates(),

                'portCategories' => $this->masterDataService
                    ->getPortCategories(),

                /*
                |--------------------------------------------------------------------------
                | AJAX Loaded
                |--------------------------------------------------------------------------
                */

                'stateBoards' => collect(),

                'ports' => collect(),

                'reportingOfficers' => $this->masterDataService
                    ->getReportingOfficers(),

            ],

            message: 'Unable to load dropdown data.'

        );
    }

    /**
     * Generate Unique Employee Code.
     *
     * Format:
     * EMP000001
     *
     * @throws EmployeeException
     */
    private function generateEmployeeCode(): string
    {
        return $this->executeEmployeeQuery(

            callback: function () {

                $lastEmployee = User::query()

                    ->lockForUpdate()

                    ->latest('id')

                    ->first();

                if (
                    !$lastEmployee ||
                    empty($lastEmployee->employee_code)
                ) {
                    return 'EMP000001';
                }

                $lastNumber = (int) preg_replace(
                    '/[^0-9]/',
                    '',
                    $lastEmployee->employee_code
                );

                return 'EMP' . str_pad(

                    $lastNumber + 1,

                    6,

                    '0',

                    STR_PAD_LEFT

                );
            },

            message: 'Unable to generate employee code.'

        );
    }

    /**
     * Create Employee.
     *
     * @throws EmployeeException
     */
    public function createEmployee(
        array $data
    ): User {

        return $this->executeEmployeeQuery(

            callback: fn() => DB::transaction(

                function () use ($data) {

                    /*
                    |--------------------------------------------------------------------------
                    | Employee Code
                    |--------------------------------------------------------------------------
                    */

                    if (empty($data['employee_code'])) {

                        $data['employee_code'] =
                            $this->generateEmployeeCode();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Full Name
                    |--------------------------------------------------------------------------
                    */

                    $data['name'] = trim(

                        implode(

                            ' ',

                            array_filter([

                                $data['first_name'],

                                $data['middle_name'] ?? null,

                                $data['last_name'],

                            ])

                        )

                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Password
                    |--------------------------------------------------------------------------
                    */

                    $data['password'] = Hash::make(
                        $data['password']
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Default Values
                    |--------------------------------------------------------------------------
                    */

                    $data['status'] = $data['status'] ?? true;

                    $data['force_password_change'] = true;

                    $data['is_deleted'] = false;

                    /*
                    |--------------------------------------------------------------------------
                    | Audit Fields
                    |--------------------------------------------------------------------------
                    */

                    if (auth()->check()) {

                        $data['created_by'] = auth()->id();

                        $data['updated_by'] = auth()->id();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Create Employee
                    |--------------------------------------------------------------------------
                    */

                    $employee = User::create($data);

                    // dd(
                    //     $data,
                    //     $employee->toArray()
                    // );

                    /*
                    |--------------------------------------------------------------------------
                    | Future Integrations
                    |--------------------------------------------------------------------------
                    */

                    // $this->createAuditLog($employee);

                    // $this->sendWelcomeMail($employee);

                    // $this->assignDefaultPermissions($employee);

                    // $this->notifyReportingOfficer($employee);

                    return $employee->fresh(
                        self::RELATIONS
                    );
                }

            ),

            message: 'Unable to create employee.'

        );
    }

    /**
     * Update Employee.
     *
     * @throws EmployeeException
     */
    public function updateEmployee(
        User $user,
        array $data
    ): User {

        return $this->executeEmployeeQuery(

            callback: fn() => DB::transaction(

                function () use ($user, $data) {

                    /*
                    |--------------------------------------------------------------------------
                    | Full Name
                    |--------------------------------------------------------------------------
                    */

                    $data['name'] = trim(

                        implode(

                            ' ',

                            array_filter([

                                $data['first_name'],

                                $data['middle_name'] ?? null,

                                $data['last_name'],

                            ])

                        )

                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Password
                    |--------------------------------------------------------------------------
                    | Password update handled by PasswordService.
                    |--------------------------------------------------------------------------
                    */

                    unset(

                        $data['password'],

                        $data['password_confirmation']

                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Audit Fields
                    |--------------------------------------------------------------------------
                    */

                    if (auth()->check()) {

                        $data['updated_by'] = auth()->id();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Update Employee
                    |--------------------------------------------------------------------------
                    */

                    $user->update($data);

                    return $user->fresh(
                        self::RELATIONS
                    );
                }

            ),

            message: 'Unable to update employee.'

        );
    }

    /**
     * Soft Delete Employee.
     *
     * @throws EmployeeException
     */
    public function deleteEmployee(
        User $user
    ): bool {

        return $this->executeEmployeeQuery(

            callback: fn() => DB::transaction(

                function () use ($user) {

                    return $user->update([

                        'status'      => false,

                        'is_deleted'  => true,

                        'updated_by'  => auth()->id(),

                    ]);
                }

            ),

            message: 'Unable to delete employee.'

        );
    }

    /**
     * Change Employee Status.
     *
     * @throws EmployeeException
     */
    public function changeStatus(
        User $user
    ): User {

        return $this->executeEmployeeQuery(

            callback: fn() => DB::transaction(

                function () use ($user) {

                    $user->update([

                        'status' => ! $user->status,

                        'updated_by' => auth()->id(),

                    ]);

                    return $user->fresh(
                        self::RELATIONS
                    );
                }

            ),

            message: 'Unable to update employee status.'

        );
    }
}
