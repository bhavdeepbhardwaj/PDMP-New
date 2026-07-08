{{-- resources/views/employees/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')

    <div class="container-fluid">

        {{-- ========================================================= --}}
        {{-- Page Header --}}
        {{-- ========================================================= --}}

        <div class="row mb-3">

            <div class="col-md-6">

                <h3 class="mb-0">
                    Employee Management
                </h3>

                <small class="text-muted">
                    Manage Employees
                </small>

            </div>

            <div class="col-md-6 text-end">

                <a href="{{ route('employees.create') }}" class="btn btn-primary">

                    <i class="fa fa-plus"></i>

                    Add Employee

                </a>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Flash Messages --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>
        @endif

        {{-- ========================================================= --}}
        {{-- Validation Errors --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- Search & Filters --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Search & Filters

                </h5>

            </div>

            <div class="card-body">

                <form method="GET" action="{{ route('employees.index') }}">

                    <div class="row">

                        {{-- Search --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Search

                            </label>

                            <input type="text" name="search" class="form-control"
                                placeholder="Employee Code / Name / Username / Email / Mobile"
                                value="{{ request('search') }}">

                        </div>

                        {{-- Organization --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Organization

                            </label>

                            <select name="organization_id" class="form-select auto-submit">

                                <option value="">

                                    All Organizations

                                </option>

                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}" @selected(request('organization_id') == $organization->id)>

                                        {{ $organization->organization_name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- Department --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Department

                            </label>

                            <select name="department_id" class="form-select auto-submit">

                                <option value="">

                                    All Departments

                                </option>

                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>

                                        {{ $department->department_name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="row">

                        {{-- Role --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Role

                            </label>

                            <select name="role_id" class="form-select auto-submit">

                                <option value="">

                                    All Roles

                                </option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>

                                        {{ $role->role_name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- State --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                State

                            </label>

                            <select name="state_id" class="form-select auto-submit">

                                <option value="">

                                    All States

                                </option>

                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" @selected(request('state_id') == $state->id)>

                                        {{ $state->state_name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- State Board --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                State Board

                            </label>

                            <select name="state_board_id" class="form-select auto-submit">

                                <option value="">

                                    All Boards

                                </option>

                                @foreach ($stateBoards as $board)
                                    <option value="{{ $board->id }}" @selected(request('state_board_id') == $board->id)>

                                        {{ $board->board_name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- Port --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Port

                            </label>

                            <select name="port_id" class="form-select auto-submit">

                                <option value="">

                                    All Ports

                                </option>

                                @foreach ($ports as $port)
                                    <option value="{{ $port->id }}" @selected(request('port_id') == $port->id)>

                                        {{ $port->port_name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="row">

                        {{-- Status --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Status

                            </label>

                            <select name="status" class="form-select auto-submit">

                                <option value="">

                                    All

                                </option>

                                <option value="1" @selected(request('status') === '1')>

                                    Active

                                </option>

                                <option value="0" @selected(request('status') === '0')>

                                    Inactive

                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- Buttons --}}
                    {{-- ========================================================= --}}

                    <div class="mt-3">

                        <button type="submit" class="btn btn-primary">

                            <i class="fa fa-search"></i>

                            Search

                        </button>

                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">

                            <i class="fa fa-refresh"></i>

                            Reset

                        </a>

                    </div>

                </form>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Employee List --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    Employee List ({{ $employees->total() }})
                </h5>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover table-striped align-middle mb-0 text-nowrap"
                        id="searchForm">

                        <thead class="table-dark">

                            <tr>

                                <th width="60">#</th>

                                <th>Employee Code</th>

                                <th>Employee Name</th>

                                <th>Email</th>

                                <th>Department</th>

                                <th width="120">Mobile</th>

                                <th>Role</th>

                                <th>Port</th>

                                <th>Status</th>

                                <th width="220" class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($employees as $employee)
                                <tr>

                                    <td>

                                        {{ $employees->firstItem() + $loop->index }}

                                    </td>

                                    <td>

                                        {{ $employee->employee_code }}

                                    </td>

                                    <td>

                                        {{ $employee->full_name }}

                                    </td>

                                    <td>

                                        {{ $employee->email }}

                                    </td>

                                    <td>

                                        {{ $employee->department?->department_name ?? '-' }}

                                    </td>

                                    <td>

                                        {{ $employee->mobile_number }}

                                    </td>

                                    <td>

                                        {{ $employee->role?->role_name ?? '-' }}

                                    </td>

                                    <td>

                                        @if ($employee->assignedPorts->count())
                                            @foreach ($employee->assignedPorts as $port)
                                                <span class="badge bg-primary">

                                                    {{ $port->port_name }}

                                                </span>
                                            @endforeach
                                        @else
                                            {{ $employee->port?->port_name ?? '-' }}
                                        @endif

                                    </td>

                                    <td>

                                        @if ($employee->status)
                                            <span class="badge bg-success">

                                                Active

                                            </span>
                                        @else
                                            <span class="badge bg-danger">

                                                Inactive

                                            </span>
                                        @endif

                                    </td>

                                    <td class="text-center">

                                        {{-- View --}}

                                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info"
                                            title="View Employee">

                                            <i class="fa fa-eye"></i>

                                        </a>

                                        {{-- Edit --}}

                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning"
                                            title="Edit Employee">

                                            <i class="fa fa-edit"></i>

                                        </a>

                                        {{-- Status Toggle --}}

                                        <form action="{{ route('employees.status', $employee) }}" method="POST"
                                            class="d-inline" title="{{ $employee->status ? 'Deactivate' : 'Activate' }}">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit"
                                                class="btn btn-sm {{ $employee->status ? 'btn-secondary' : 'btn-success' }}"
                                                title="Change Status">

                                                @if ($employee->status)
                                                    <i class="fa fa-toggle-on"></i>
                                                @else
                                                    <i class="fa fa-toggle-off"></i>
                                                @endif

                                            </button>

                                        </form>

                                        {{-- Delete --}}

                                        <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                            class="d-inline delete-form" title="Delete Employee">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center text-muted py-4">

                                        <i class="fa fa-folder-open fa-2x mb-2"></i>

                                        <br>

                                        No employee records found for the selected filters.

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- Pagination --}}
            {{-- ========================================================= --}}

            @if ($employees->hasPages())
                <div class="card-footer">

                    <div class="row align-items-center">

                        <div class="col-md-6">

                            <small class="text-muted">

                                Showing

                                <strong>{{ $employees->firstItem() }}</strong>

                                to

                                <strong>{{ $employees->lastItem() }}</strong>

                                of

                                <strong>{{ $employees->total() }}</strong>

                                employees

                            </small>

                        </div>

                        <div class="col-md-6 d-flex justify-content-end">

                            {{ $employees->withQueryString()->links() }}

                        </div>

                    </div>

                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | Auto Close Flash Messages
            |--------------------------------------------------------------------------
            */

            setTimeout(function() {

                $('.alert').fadeOut('slow');

            }, 4000);

            /*
            |--------------------------------------------------------------------------
            | SweetAlert Delete Confirmation
            |--------------------------------------------------------------------------
            */

            $('.delete-form').on('submit', function(e) {

                e.preventDefault();

                let form = this;

                Swal.fire({

                    title: 'Delete Employee?',

                    text: 'This employee will be marked as deleted.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonColor: '#d33',

                    cancelButtonColor: '#6c757d',

                    confirmButtonText: 'Yes, Delete',

                    cancelButtonText: 'Cancel'

                }).then((result) => {

                    if (result.isConfirmed) {

                        form.submit();

                    }

                });

            });

            /*
            |--------------------------------------------------------------------------
            | Optional Auto Submit Filters
            |--------------------------------------------------------------------------
            */

            $('.auto-submit').on('change', function() {

                $(this).closest('form').submit();

            });

        });
    </script>
@endpush
