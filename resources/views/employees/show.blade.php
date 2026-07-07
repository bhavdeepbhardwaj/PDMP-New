{{-- resources/views/employees/show.blade.php --}}

@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')

    <div class="container-fluid">

        {{-- ========================================================= --}}
        {{-- Page Header --}}
        {{-- ========================================================= --}}

        <div class="row mb-3">

            <div class="col-md-6">

                <h3 class="mb-0">
                    Employee Details
                </h3>

                <small class="text-muted">
                    View Employee Information
                </small>

            </div>

            <div class="col-md-6 text-end">

                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">

                    <i class="fa fa-edit"></i>

                    Edit

                </a>

                <a href="{{ route('employees.index') }}" class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>

                    Back

                </a>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Employee Profile --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Employee Profile

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <strong>Employee Code</strong>

                        <p>{{ $employee->employee_code }}</p>

                    </div>

                    <div class="col-md-3">

                        <strong>Title</strong>

                        <p>{{ $employee->title ?? '-' }}</p>

                    </div>

                    <div class="col-md-6">

                        <strong>Full Name</strong>

                        <p>{{ $employee->full_name }}</p>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Account Information --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Account Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <strong>Email</strong>

                        <p>{{ $employee->email }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>Username</strong>

                        <p>{{ $employee->username }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>Status</strong>

                        <p>

                            @if ($employee->status)
                                <span class="badge bg-success">

                                    Active

                                </span>
                            @else
                                <span class="badge bg-danger">

                                    Inactive

                                </span>
                            @endif

                        </p>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Organization Information --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Organization Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <strong>Organization</strong>

                        <p>{{ $employee->organization?->organization_name ?? '-' }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>Department</strong>

                        <p>{{ $employee->department?->department_name ?? '-' }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>Role</strong>

                        <p>{{ $employee->role?->role_name ?? '-' }}</p>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-4">

                        <strong>State</strong>

                        <p>{{ $employee->state?->state_name ?? '-' }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>State Board</strong>

                        <p>{{ $employee->stateBoard?->board_name ?? '-' }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>Port</strong>

                        <p>{{ $employee->port?->port_name ?? '-' }}</p>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <strong>Reporting Officer</strong>

                        <p>{{ $employee->reportingOfficer?->full_name ?? '-' }}</p>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Contact Information --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Contact Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <strong>Mobile Number</strong>

                        <p>{{ $employee->mobile_number ?? '-' }}</p>

                    </div>

                    <div class="col-md-8">

                        <strong>Official Address</strong>

                        <p>{{ $employee->official_address ?? '-' }}</p>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- Audit Information --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">

                    Audit Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <strong>Created At</strong>

                        <p>{{ $employee->created_at?->format('d-m-Y h:i A') ?? '-' }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>Updated At</strong>

                        <p>{{ $employee->updated_at?->format('d-m-Y h:i A') ?? '-' }}</p>

                    </div>

                    <div class="col-md-4">

                        <strong>Password Changed</strong>

                        <p>{{ $employee->password_changed_at?->format('d-m-Y h:i A') ?? '-' }}</p>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
