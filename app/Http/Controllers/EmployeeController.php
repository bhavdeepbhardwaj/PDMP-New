<?php

namespace App\Http\Controllers;

use App\Exceptions\EmployeeException;
use App\Http\Requests\EmployeeRequest;
use App\Models\User;
use App\Services\EmployeeService;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class EmployeeController extends Controller
{
    /**
     * Employee Service Instance.
     */
    protected EmployeeService $employeeService;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        EmployeeService $employeeService
    ) {
        $this->employeeService = $employeeService;
    }

    /**
     * Execute Employee Action.
     */
    private function execute(
        Closure $callback,
        string $errorMessage,
        bool $withInput = false,
        ?string $redirectRoute = null
    ) {
        try {

            return $callback();
        } catch (EmployeeException $e) {

            $response = $redirectRoute
                ? redirect()->route($redirectRoute)
                : back();

            if ($withInput) {
                $response->withInput();
            }

            return $response->with(
                'error',
                $e->getMessage()
            );
        } catch (Throwable $e) {

            report($e);

            $response = $redirectRoute
                ? redirect()->route($redirectRoute)
                : back();

            if ($withInput) {
                $response->withInput();
            }

            return $response->with(
                'error',
                $errorMessage
            );
        }
    }

    /**
     * Display Employee Listing.
     */
    public function index(Request $request): View|RedirectResponse
    {
        return $this->execute(

            callback: function () use ($request) {

                $employees = $this->employeeService
                    ->getEmployees($request->all());

                $dropdowns = $this->employeeService
                    ->getDropdownData();

                return view(
                    'employees.index',
                    array_merge(
                        compact('employees'),
                        $dropdowns
                    )
                );
            },

            errorMessage: 'Unable to load employees.'

        );
    }

    /**
     * Show Employee Create Form.
     */
    public function create(): View|RedirectResponse
    {
        return $this->execute(

            callback: fn() => view(
                'employees.create',
                $this->employeeService->getDropdownData()
            ),

            errorMessage: 'Unable to load employee form.',

            redirectRoute: 'employees.index'

        );
    }

    /**
     * Store Employee.
     */
    public function store(
        EmployeeRequest $request
    ): RedirectResponse {
        return $this->execute(

            callback: function () use ($request) {

                $this->employeeService->createEmployee(
                    $request->validated()
                );

                return redirect()
                    ->route('employees.index')
                    ->with(
                        'success',
                        'Employee created successfully.'
                    );
            },

            errorMessage: 'Unable to create employee.',

            withInput: true

        );
    }

    /**
     * Display Employee Details.
     */
    public function show(
        User $employee
    ): View|RedirectResponse {
        return $this->execute(

            callback: fn() => view(
                'employees.show',
                compact('employee')
            ),

            errorMessage: 'Unable to load employee details.',

            redirectRoute: 'employees.index'

        );
    }

    /**
     * Show Employee Edit Form.
     */
    public function edit(
        User $employee
    ): View|RedirectResponse {
        return $this->execute(

            callback: function () use ($employee) {

                return view(
                    'employees.edit',
                    array_merge(
                        $this->employeeService
                            ->getDropdownData(),
                        compact('employee')
                    )
                );
            },

            errorMessage: 'Unable to load employee.',

            redirectRoute: 'employees.index'

        );
    }

    /**
     * Update Employee.
     */
    public function update(
        EmployeeRequest $request,
        User $employee
    ): RedirectResponse {
        return $this->execute(

            callback: function () use (
                $request,
                $employee
            ) {

                $this->employeeService
                    ->updateEmployee(
                        $employee,
                        $request->validated()
                    );

                return redirect()
                    ->route('employees.index')
                    ->with(
                        'success',
                        'Employee updated successfully.'
                    );
            },

            errorMessage: 'Unable to update employee.',

            withInput: true

        );
    }

    /**
     * Delete Employee.
     */
    public function destroy(
        User $employee
    ): RedirectResponse {
        return $this->execute(

            callback: function () use ($employee) {

                $this->employeeService
                    ->deleteEmployee($employee);

                return redirect()
                    ->route('employees.index')
                    ->with(
                        'success',
                        'Employee deleted successfully.'
                    );
            },

            errorMessage: 'Unable to delete employee.',

            redirectRoute: 'employees.index'

        );
    }

    /**
     * Change Employee Status.
     */
    public function changeStatus(
        User $employee
    ): RedirectResponse {
        return $this->execute(

            callback: function () use ($employee) {

                $this->employeeService
                    ->changeStatus($employee);

                return back()->with(
                    'success',
                    'Employee status updated successfully.'
                );
            },

            errorMessage: 'Unable to update employee status.'

        );
    }
}
