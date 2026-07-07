{{-- resources/views/employees/partials/form.blade.php --}}

<div class="card shadow-sm">

    <div class="card-header">
        <h5 class="mb-0">
            Employee Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row">

            {{-- Employee Code --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">
                    Employee Code
                </label>

                <input type="text" class="form-control"
                    value="{{ old('employee_code', $employee->employee_code ?? '') }}" readonly>

                @error('employee_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Title --}}
            <div class="col-md-2 mb-3">
                <label class="form-label">
                    Title <span class="text-danger">*</span>
                </label>

                <select name="title" class="form-select">

                    <option value="">Select</option>

                    @foreach (['Mr', 'Mrs', 'Ms', 'Dr'] as $title)
                        <option value="{{ $title }}" @selected(old('title', $employee->title ?? '') == $title)>
                            {{ $title }}
                        </option>
                    @endforeach

                </select>

                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            {{-- First Name --}}
            <div class="col-md-3 mb-3">

                <label class="form-label">
                    First Name <span class="text-danger">*</span>
                </label>

                <input type="text" name="first_name" class="form-control"
                    value="{{ old('first_name', $employee->first_name ?? '') }}">

                @error('first_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            {{-- Middle Name --}}
            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Middle Name
                </label>

                <input type="text" name="middle_name" class="form-control"
                    value="{{ old('middle_name', $employee->middle_name ?? '') }}">

                @error('middle_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            {{-- Last Name --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Last Name <span class="text-danger">*</span>
                </label>

                <input type="text" name="last_name" class="form-control"
                    value="{{ old('last_name', $employee->last_name ?? '') }}">

                @error('last_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

        </div>

    </div>
</div>

{{-- Account Information --}}

<div class="card shadow-sm mt-4">

    <div class="card-header">
        <h5 class="mb-0">
            Account Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row">

            {{-- Email --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Email <span class="text-danger">*</span>
                </label>

                <input type="email" name="email" class="form-control"
                    value="{{ old('email', $employee->email ?? '') }}">

                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            {{-- Username --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Username <span class="text-danger">*</span>
                </label>

                <input type="text" name="username" class="form-control"
                    value="{{ old('username', $employee->username ?? '') }}" autocomplete="off">

                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            @if (!isset($employee))
                {{-- Password --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Password <span class="text-danger">*</span>
                    </label>

                    <input type="password" name="password" class="form-control" autocomplete="off">

                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>

                {{-- Confirm Password --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Confirm Password
                    </label>

                    <input type="password" name="password_confirmation" class="form-control">

                </div>
            @endif

        </div>

    </div>

</div>

{{-- Organization Information --}}

<div class="card shadow-sm mt-4">

    <div class="card-header">
        <h5 class="mb-0">
            Organization Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row">

            {{-- Organization --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">Organization</label>

                <select name="organization_id" class="form-select">

                    <option value="">Select</option>

                    @foreach ($organizations as $organization)
                        <option value="{{ $organization->id }}" @selected(old('organization_id', $employee->organization_id ?? '') == $organization->id)>

                            {{ $organization->organization_name }}

                        </option>
                    @endforeach

                </select>

                @error('organization_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            {{-- Department --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">Department</label>

                <select name="department_id" class="form-select">

                    <option value="">Select</option>

                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id', $employee->department_id ?? '') == $department->id)>

                            {{ $department->department_name }}

                        </option>
                    @endforeach

                </select>

                @error('department_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            {{-- Role --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">Role</label>

                <select name="role_id" class="form-select">

                    <option value="">Select</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $employee->role_id ?? '') == $role->id)>

                            {{ $role->role_name }}

                        </option>
                    @endforeach

                </select>

                @error('role_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

        </div>

        <div class="row">

            {{-- ========================================================= --}}
            {{-- State --}}
            {{-- ========================================================= --}}
            <div class="col-md-3 mb-3">

                <label for="state_id" class="form-label">

                    State <span class="text-danger">*</span>

                </label>

                <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror">

                    <option value="">Select State</option>

                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" @selected(old('state_id', $employee->state_id ?? '') == $state->id)>

                            {{ $state->state_name }}

                        </option>
                    @endforeach

                </select>

                @error('state_id')
                    <div class="invalid-feedback">

                        {{ $message }}

                    </div>
                @enderror

            </div>

            {{-- ========================================================= --}}
            {{-- Port Type --}}
            {{-- ========================================================= --}}
            <div class="col-md-4 mb-3">

                <label for="port_type_id" class="form-label">
                    Port Type <span class="text-danger">*</span>
                </label>

                <select name="port_type_id" id="port_type_id"
                    class="form-select @error('port_type_id') is-invalid @enderror">

                    <option value="">Select Port Type</option>

                    <option value="1" @selected(old('port_type_id', $employee->port_type_id ?? '') == 1)>
                        Major Port
                    </option>

                    <option value="2" @selected(old('port_type_id', $employee->port_type_id ?? '') == 2)>
                        Non Major Port
                    </option>

                </select>

                @error('port_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            {{-- ========================================================= --}}
            {{-- State Board --}}
            {{-- ========================================================= --}}
            <div class="col-md-4 mb-3">

                <label for="state_board_id" class="form-label">
                    State Board
                </label>

                <select name="state_board_id" id="state_board_id"
                    class="form-select @error('state_board_id') is-invalid @enderror">

                    <option value="">Select State Board</option>

                </select>

                @error('state_board_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            {{-- ========================================================= --}}
            {{-- Port --}}
            {{-- ========================================================= --}}
            <div class="col-md-4 mb-3">

                <label for="port_id" class="form-label">
                    Port <span class="text-danger">*</span>
                </label>

                <select name="port_id" id="port_id" class="form-select @error('port_id') is-invalid @enderror">

                    <option value="">Select Port</option>

                </select>

                @error('port_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Reporting Officer --}}
        {{-- ========================================================= --}}
        <div class="col-md-3 mb-3">

            <label for="report_to_user_id" class="form-label">

                Reporting Officer

            </label>

            <select name="report_to_user_id" id="report_to_user_id"
                class="form-select @error('report_to_user_id') is-invalid @enderror">

                <option value="">Select Reporting Officer</option>

                @foreach ($reportingOfficers as $officer)
                    <option value="{{ $officer->id }}" @selected(old('report_to_user_id', $employee->report_to_user_id ?? '') == $officer->id)>

                        {{ $officer->employee_code }}
                        -
                        {{ $officer->full_name }}

                    </option>
                @endforeach

            </select>

            @error('report_to_user_id')
                <div class="invalid-feedback">

                    {{ $message }}

                </div>
            @enderror

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- Hidden Values for AJAX Edit Mode --}}
    {{-- ========================================================= --}}

    <input type="hidden" id="selected_state_board"
        value="{{ old('state_board_id', $employee->state_board_id ?? '') }}">

    <input type="hidden" id="selected_port" value="{{ old('port_id', $employee->port_id ?? '') }}">

</div>

</div>

{{-- Contact Information --}}

<div class="card shadow-sm mt-4">

    <div class="card-header">
        <h5 class="mb-0">
            Contact Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Mobile Number
                </label>

                <input type="text" name="mobile_number" class="form-control"
                    value="{{ old('mobile_number', $employee->mobile_number ?? '') }}">

            </div>

            <div class="col-md-8 mb-3">

                <label class="form-label">
                    Official Address
                </label>

                <textarea name="official_address" rows="3" class="form-control">{{ old('official_address', $employee->official_address ?? '') }}</textarea>

            </div>

        </div>

    </div>

</div>

@if (isset($employee))
    <div class="card shadow-sm mt-4">

        <div class="card-header">
            <h5 class="mb-0">
                Status
            </h5>
        </div>

        <div class="card-body">

            <select name="status" class="form-select w-25">

                <option value="1" @selected(old('status', $employee->status) == 1)>
                    Active
                </option>

                <option value="0" @selected(old('status', $employee->status) == 0)>
                    Inactive
                </option>

            </select>

        </div>

    </div>
@else
    <input type="hidden" name="status" value="1">
@endif

<div class="mt-4">

    <button type="submit" class="btn btn-primary">

        <i class="fa fa-save"></i>

        Save

    </button>

    <a href="{{ route('employees.index') }}" class="btn btn-secondary">

        Cancel

    </a>

</div>

@push('scripts')
    <script>
        $(function() {

            const PORT_TYPE_MAJOR = 1;
            const PORT_TYPE_NON_MAJOR = 2;

            const portType = $('#port_type_id');
            const stateBoard = $('#state_board_id');
            const port = $('#port_id');

            const selectedStateBoard = $('#selected_state_board').val();
            const selectedPort = $('#selected_port').val();

            function resetStateBoard() {
                stateBoard.html(
                    '<option value="">Select State Board</option>'
                );
            }

            function resetPort() {
                port.html(
                    '<option value="">Select Port</option>'
                );
            }

            function hideStateBoard() {
                stateBoard
                    .closest('.col-md-4')
                    .hide();
            }

            function showStateBoard() {
                stateBoard
                    .closest('.col-md-4')
                    .show();
            }

            function loadStateBoards(callback = null) {
                resetStateBoard();

                $.get(
                    '/ajax/master/state-boards',
                    function(response) {

                        if (!response.status) {
                            return;
                        }

                        $.each(response.data, function(_, item) {

                            stateBoard.append(

                                `<option value="${item.id}">
                            ${item.board_name}
                        </option>`

                            );

                        });

                        if (selectedStateBoard) {

                            stateBoard.val(selectedStateBoard);

                        }

                        if (callback) {
                            callback();
                        }

                    }
                );
            }

            function loadPortsByCategory(callback = null) {

                resetPort();

                $.get(
                    '/ajax/master/port-categories/' + portType.val() + '/ports',
                    function(response) {

                        if (!response.status) {
                            return;
                        }

                        $.each(response.data, function(_, item) {

                            port.append(
                                `<option value="${item.id}">
                    ${item.port_name}
                </option>`
                            );

                        });

                        if (selectedPort) {
                            port.val(selectedPort);
                        }

                        if (callback) {
                            callback();
                        }

                    }
                );

            }

            function loadPortsByStateBoard() {

                resetPort();

                $.get(

                    '/ajax/master/state-boards/' + stateBoard.val() + '/ports',

                    function(response) {

                        if (!response.status) {
                            return;
                        }

                        $.each(response.data, function(_, item) {

                            port.append(
                                `<option value="${item.id}">
                        ${item.port_name}
                    </option>`
                            );

                        });

                        if (selectedPort) {
                            port.val(selectedPort);
                        }

                    }

                );
            }

            /*
            |--------------------------------------------------------------------------
            | Port Type Change
            |--------------------------------------------------------------------------
            */

            portType.change(function() {

                resetPort();
                resetStateBoard();

                if ($(this).val() == PORT_TYPE_MAJOR) {

                    hideStateBoard();

                    loadPortsByCategory();

                } else if ($(this).val() == PORT_TYPE_NON_MAJOR) {

                    showStateBoard();

                    loadStateBoards();

                } else {

                    hideStateBoard();

                }

            });

            /*
            |--------------------------------------------------------------------------
            | State Board Change
            |--------------------------------------------------------------------------
            */

            stateBoard.change(function() {

                if ($(this).val()) {

                    loadPortsByStateBoard();

                }

            });

            /*
            |--------------------------------------------------------------------------
            | Edit Mode
            |--------------------------------------------------------------------------
            */

            if (portType.val() == PORT_TYPE_MAJOR) {

                hideStateBoard();

                loadPortsByCategory();

            } else if (portType.val() == PORT_TYPE_NON_MAJOR) {

                showStateBoard();

                loadStateBoards(function() {

                    loadPortsByStateBoard();

                });

            } else {

                hideStateBoard();

            }

        });
    </script>
@endpush
