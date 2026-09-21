$(function () {
    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const PORT_TYPE_MAJOR = "1";
    const PORT_TYPE_NON_MAJOR = "2";

    const ACCESS_ALL = "ALL";
    const ACCESS_PORT = "PORT";
    const ACCESS_STATE_BOARD = "STATE_BOARD";

    const ASSIGNMENT_SINGLE = "SINGLE";
    const ASSIGNMENT_MULTIPLE = "MULTIPLE";

    /*
    |--------------------------------------------------------------------------
    | DOM Elements
    |--------------------------------------------------------------------------
    */

    const role = $("#role_id");

    const portType = $("#port_type_id");
    const stateBoard = $("#state_board_id");
    const port = $("#port_id");
    const multiplePorts = $("#ports");

    const portTypeWrapper = $("#port_type_wrapper");
    const stateBoardWrapper = $("#state_board_wrapper");
    const portWrapper = $("#port_wrapper");
    const multiplePortsWrapper = $("#multiple_ports_wrapper");

    const selectedStateBoard = $("#selected_state_board");
    const selectedPort = $("#selected_port");
    const selectedPorts = $("#selected_ports");

    /*
    |--------------------------------------------------------------------------
    | Master URL
    |--------------------------------------------------------------------------
    */

    const MASTER_URL = "/ajax/master";

    /*
    |--------------------------------------------------------------------------
    | Select2 Initialization
    |--------------------------------------------------------------------------
    */

    if ($.fn.select2) {
        portType.select2({
            width: "100%",
            placeholder: "Please Select Port Type",
            allowClear: true,
        });

        stateBoard.select2({
            width: "100%",
            placeholder: "Please Select State Board",
            allowClear: true,
        });

        port.select2({
            width: "100%",
            placeholder: "Please Select Port",
            allowClear: true,
        });

        multiplePorts.select2({
            width: "100%",
            placeholder: "Please Select Ports",
            allowClear: true,
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function getRoleAccessScope() {
        return String(
            role.find(":selected").data("access-scope") || "",
        ).toUpperCase();
    }

    function getRoleAssignmentType() {
        return String(
            role.find(":selected").data("assignment-type") || "",
        ).toUpperCase();
    }

    function isAllAccess() {
        return getRoleAccessScope() === ACCESS_ALL;
    }

    function isStateBoardAccess() {
        return getRoleAccessScope() === ACCESS_STATE_BOARD;
    }

    function isPortAccess() {
        return getRoleAccessScope() === ACCESS_PORT;
    }

    function isMultipleAssignment() {
        return getRoleAssignmentType() === ASSIGNMENT_MULTIPLE;
    }

    function isSingleAssignment() {
        return getRoleAssignmentType() === ASSIGNMENT_SINGLE;
    }

    /*
    |--------------------------------------------------------------------------
    | Select Helpers
    |--------------------------------------------------------------------------
    */

    function clearSelect($select) {
        $select.val(null).trigger("change");
    }

    function disableSelect($select) {
        $select.val(null).prop("disabled", true).trigger("change");
    }

    function enableSelect($select) {
        $select.prop("disabled", false);
    }

    function clearPortAssignmentFields() {
        /*
        | Clear actual form fields
        */
        clearSelect(portType);
        clearSelect(stateBoard);
        clearSelect(port);
        clearSelect(multiplePorts);

        /*
        | Clear edit-mode restore values
        */
        selectedStateBoard.val("");
        selectedPort.val("");
        selectedPorts.val("");
    }

    function hideAllPortSections() {
        portTypeWrapper.hide();
        stateBoardWrapper.hide();
        portWrapper.hide();
        multiplePortsWrapper.hide();
    }

    function disableAllPortSections() {
        disableSelect(portType);
        disableSelect(stateBoard);
        disableSelect(port);
        disableSelect(multiplePorts);
    }

    function enableAllPortSections() {
        enableSelect(portType);
        enableSelect(stateBoard);
        enableSelect(port);
        enableSelect(multiplePorts);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX Helper
    |--------------------------------------------------------------------------
    */

    function ajaxGet(url) {
        return $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Load State Boards
    |--------------------------------------------------------------------------
    */

    function loadStateBoards(restoreValue = null) {
        return new Promise(function (resolve, reject) {
            stateBoard.empty();

            stateBoard.append(new Option("Please Select State Board", ""));

            ajaxGet(MASTER_URL + "/state-boards")
                .done(function (response) {
                    if (
                        response &&
                        response.status === true &&
                        Array.isArray(response.data)
                    ) {
                        response.data.forEach(function (item) {
                            stateBoard.append(
                                new Option(item.board_name, item.id),
                            );
                        });
                    }

                    /*
                |--------------------------------------------------------------------------
                | Restore selected State Board
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | Do NOT trigger normal "change" here.
                | Otherwise loadPortsByStateBoard() /
                | loadMultiplePortsByStateBoard() can execute multiple times.
                |
                */

                    if (
                        restoreValue !== null &&
                        restoreValue !== "" &&
                        stateBoard.find('option[value="' + restoreValue + '"]')
                            .length
                    ) {
                        stateBoard
                            .val(String(restoreValue))
                            .trigger("change.select2");
                    }

                    resolve(response);
                })
                .fail(function (xhr) {
                    console.error("Unable to load State Boards.", xhr);

                    reject(xhr);
                });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Load Ports By Port Type
    |--------------------------------------------------------------------------
    */

    function loadPortsByCategory(restoreValue = null) {
        const portTypeId = portType.val();

        if (!portTypeId) {
            clearSelect(port);
            return Promise.resolve();
        }

        return new Promise(function (resolve, reject) {
            port.empty();

            port.append(new Option("Please Select Port", ""));

            ajaxGet(MASTER_URL + "/port-categories/" + portTypeId + "/ports")
                .done(function (response) {
                    if (
                        response &&
                        response.status &&
                        Array.isArray(response.data)
                    ) {
                        response.data.forEach(function (item) {
                            port.append(new Option(item.port_name, item.id));
                        });
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Restore Port
                    |--------------------------------------------------------------------------
                    */

                    if (
                        restoreValue !== null &&
                        restoreValue !== "" &&
                        port.find('option[value="' + restoreValue + '"]').length
                    ) {
                        port.val(String(restoreValue)).trigger("change");
                    }

                    resolve(response);
                })
                .fail(function (xhr) {
                    console.error("Unable to load Ports.", xhr);

                    reject(xhr);
                });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Load Ports By State Board
    |--------------------------------------------------------------------------
    */

    function loadPortsByStateBoard(restoreValue = null) {
        const stateBoardId = stateBoard.val();

        if (!stateBoardId) {
            clearSelect(port);
            return Promise.resolve();
        }

        return new Promise(function (resolve, reject) {
            port.empty();

            port.append(new Option("Please Select Port", ""));

            ajaxGet(MASTER_URL + "/state-boards/" + stateBoardId + "/ports")
                .done(function (response) {
                    if (
                        response &&
                        response.status &&
                        Array.isArray(response.data)
                    ) {
                        response.data.forEach(function (item) {
                            port.append(new Option(item.port_name, item.id));
                        });
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Restore Single Port
                    |--------------------------------------------------------------------------
                    */

                    if (
                        restoreValue !== null &&
                        restoreValue !== "" &&
                        port.find('option[value="' + restoreValue + '"]').length
                    ) {
                        port.val(String(restoreValue)).trigger("change");
                    }

                    resolve(response);
                })
                .fail(function (xhr) {
                    console.error("Unable to load Ports by State Board.", xhr);

                    reject(xhr);
                });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Load Multiple Ports By State Board
    |--------------------------------------------------------------------------
    */

    function loadMultiplePortsByStateBoard(restoreValues = []) {
        const stateBoardId = stateBoard.val();

        if (!stateBoardId) {
            clearSelect(multiplePorts);

            return Promise.resolve();
        }

        return new Promise(function (resolve, reject) {
            multiplePorts.empty();

            multiplePorts.append(new Option("Please Select Ports", ""));

            ajaxGet(MASTER_URL + "/state-boards/" + stateBoardId + "/ports")
                .done(function (response) {
                    /*
                |--------------------------------------------------------------------------
                | Safety: clear again before inserting AJAX response
                |--------------------------------------------------------------------------
                */

                    multiplePorts.empty();

                    if (
                        response &&
                        response.status === true &&
                        Array.isArray(response.data)
                    ) {
                        response.data.forEach(function (item) {
                            multiplePorts.append(
                                new Option(item.port_name, item.id),
                            );
                        });
                    }

                    /*
                |--------------------------------------------------------------------------
                | Restore selected ports
                |--------------------------------------------------------------------------
                */

                    const values = Array.isArray(restoreValues)
                        ? restoreValues.map(String)
                        : [];

                    const validValues = values.filter(function (value) {
                        return (
                            multiplePorts.find('option[value="' + value + '"]')
                                .length > 0
                        );
                    });

                    multiplePorts.val(validValues).trigger("change.select2");

                    resolve(response);
                })
                .fail(function (xhr) {
                    console.error("Unable to load Ports by State Board.", xhr);

                    reject(xhr);
                });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ALL ACCESS
    |--------------------------------------------------------------------------
    |
    | Backend:
    |
    | port_id       = NULL
    | user_ports    = EMPTY
    |
    | No Port Type / State Board / Port assignment.
    |
    */

    function handleAllAccess() {
        /*
        |--------------------------------------------------------------------------
        | Hide Assignment UI
        |--------------------------------------------------------------------------
        */

        hideAllPortSections();

        /*
        |--------------------------------------------------------------------------
        | Disable Assignment Fields
        |--------------------------------------------------------------------------
        |
        | Disabled fields are not submitted by browser.
        |
        */

        disableAllPortSections();

        /*
        |--------------------------------------------------------------------------
        | Clear Assignment Values
        |--------------------------------------------------------------------------
        */

        clearPortAssignmentFields();
    }

    /*
    |--------------------------------------------------------------------------
    | STATE BOARD ACCESS
    |--------------------------------------------------------------------------
    |
    | Backend:
    |
    | access_scope    = STATE_BOARD
    | port_type_id    = 2
    | state_board_id  = required
    | ports[]         = required
    | assignment_type = MULTIPLE
    |
    */

    function handleStateBoardAccess() {
        enableAllPortSections();

        /*
    |--------------------------------------------------------------------------
    | Show required fields
    |--------------------------------------------------------------------------
    */

        portTypeWrapper.show();
        stateBoardWrapper.show();
        multiplePortsWrapper.show();

        /*
    |--------------------------------------------------------------------------
    | Single Port is not applicable
    |--------------------------------------------------------------------------
    */

        portWrapper.hide();

        disableSelect(port);

        /*
    |--------------------------------------------------------------------------
    | Force Non-Major
    |--------------------------------------------------------------------------
    */

        portType
            .val(PORT_TYPE_NON_MAJOR)
            .prop("disabled", false)
            .trigger("change.select2");

        /*
    |--------------------------------------------------------------------------
    | Clear single port
    |--------------------------------------------------------------------------
    */

        selectedPort.val("");

        /*
    |--------------------------------------------------------------------------
    | Restore State Board
    |--------------------------------------------------------------------------
    */

        const restoreStateBoard = selectedStateBoard.val() || "";

        loadStateBoards(restoreStateBoard)
            .then(function () {
                if (!stateBoard.val()) {
                    clearSelect(multiplePorts);

                    return;
                }

                const restorePorts = selectedPorts.val()
                    ? selectedPorts.val().split(",").filter(Boolean)
                    : [];

                return loadMultiplePortsByStateBoard(restorePorts);
            })
            .catch(function (error) {
                console.error("STATE_BOARD initialization failed.", error);
            });
    }

    /*
    |--------------------------------------------------------------------------
    | PORT ACCESS
    |--------------------------------------------------------------------------
    |
    | Backend:
    |
    | access_scope    = PORT
    | assignment_type = SINGLE
    | port_id         = required
    |
    | Major:
    |   State Board not applicable
    |
    | Non-Major:
    |   State Board required
    |
    */

    function handlePortAccess() {
        enableAllPortSections();

        /*
        |--------------------------------------------------------------------------
        | Show Port Type
        |--------------------------------------------------------------------------
        */

        portTypeWrapper.show();
        portWrapper.show();

        /*
        |--------------------------------------------------------------------------
        | Multiple Ports Not Used
        |--------------------------------------------------------------------------
        */

        multiplePortsWrapper.hide();

        disableSelect(multiplePorts);

        /*
        |--------------------------------------------------------------------------
        | Restore / Process Port Type
        |--------------------------------------------------------------------------
        */

        handlePortType();
    }

    /*
    |--------------------------------------------------------------------------
    | Port Type Change
    |--------------------------------------------------------------------------
    */

    function handlePortType() {
        /*
        |--------------------------------------------------------------------------
        | ALL Role
        |--------------------------------------------------------------------------
        */

        if (isAllAccess()) {
            handleAllAccess();
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | STATE_BOARD Role
        |--------------------------------------------------------------------------
        */

        if (isStateBoardAccess()) {
            /*
            | Always Non-Major
            */
            portType
                .val(PORT_TYPE_NON_MAJOR)
                .prop("disabled", true)
                .trigger("change.select2");

            stateBoardWrapper.show();
            multiplePortsWrapper.show();
            portWrapper.hide();

            disableSelect(port);

            const restoreStateBoard = selectedStateBoard.val() || "";

            loadStateBoards(restoreStateBoard).then(function () {
                if (!stateBoard.val()) {
                    return;
                }

                const restorePorts = selectedPorts.val()
                    ? selectedPorts.val().split(",").filter(Boolean)
                    : [];

                return loadMultiplePortsByStateBoard(restorePorts);
            });

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PORT Role
        |--------------------------------------------------------------------------
        */

        if (isPortAccess()) {
            portType.prop("disabled", false);

            /*
            | Existing selected Port Type
            */
            const type = portType.val();

            if (!type) {
                stateBoardWrapper.hide();
                portWrapper.show();

                disableSelect(stateBoard);
                disableSelect(multiplePorts);

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | MAJOR
            |--------------------------------------------------------------------------
            */

            if (String(type) === PORT_TYPE_MAJOR) {
                stateBoardWrapper.hide();

                disableSelect(stateBoard);

                multiplePortsWrapper.hide();
                disableSelect(multiplePorts);

                portWrapper.show();

                /*
                | Clear State Board
                */
                selectedStateBoard.val("");

                /*
                | Load Major Ports
                */
                loadPortsByCategory(selectedPort.val() || "");

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | NON-MAJOR
            |--------------------------------------------------------------------------
            */

            if (String(type) === PORT_TYPE_NON_MAJOR) {
                stateBoardWrapper.show();

                enableSelect(stateBoard);

                multiplePortsWrapper.hide();
                disableSelect(multiplePorts);

                portWrapper.show();

                /*
                | Load State Boards first.
                */
                const restoreStateBoard = selectedStateBoard.val() || "";

                loadStateBoards(restoreStateBoard).then(function () {
                    /*
                        | If State Board selected,
                        | load its Ports.
                        */
                    if (stateBoard.val()) {
                        return loadPortsByStateBoard(selectedPort.val() || "");
                    }

                    /*
                        | No State Board yet.
                        */
                    clearSelect(port);
                });

                return;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | State Board Change
    |--------------------------------------------------------------------------
    */

    function handleStateBoard() {
        if (!isPortAccess() && !isStateBoardAccess()) {
            return;
        }

        const stateBoardId = stateBoard.val();

        if (!stateBoardId) {
            clearSelect(port);
            clearSelect(multiplePorts);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | STATE_BOARD -> MULTIPLE
        |--------------------------------------------------------------------------
        */

        if (isStateBoardAccess()) {
            const restorePorts = selectedPorts.val()
                ? selectedPorts.val().split(",").filter(Boolean)
                : [];

            loadMultiplePortsByStateBoard(restorePorts);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PORT -> SINGLE
        |--------------------------------------------------------------------------
        */

        if (isPortAccess()) {
            loadPortsByStateBoard(selectedPort.val() || "");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Role Change
    |--------------------------------------------------------------------------
    */

    function handleRole() {
        /*
        |--------------------------------------------------------------------------
        | First clear current assignment UI
        |--------------------------------------------------------------------------
        */

        hideAllPortSections();

        /*
        | Enable first.
        | Individual access handlers will disable what they don't use.
        */
        enableAllPortSections();

        /*
        |--------------------------------------------------------------------------
        | No Role
        |--------------------------------------------------------------------------
        */

        if (!role.val()) {
            clearPortAssignmentFields();
            disableAllPortSections();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ALL
        |--------------------------------------------------------------------------
        */

        if (isAllAccess()) {
            handleAllAccess();
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | STATE BOARD
        |--------------------------------------------------------------------------
        */

        if (isStateBoardAccess()) {
            handleStateBoardAccess();
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PORT
        |--------------------------------------------------------------------------
        */

        if (isPortAccess()) {
            handlePortAccess();
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Unknown Scope
        |--------------------------------------------------------------------------
        */

        clearPortAssignmentFields();
        disableAllPortSections();
    }

    /*
    |--------------------------------------------------------------------------
    | Role Change Event
    |--------------------------------------------------------------------------
    */

    role.on("change", function () {
        /*
        | On an actual role change, old assignment values
        | must not leak into the new role.
        */
        selectedStateBoard.val("");
        selectedPort.val("");
        selectedPorts.val("");

        clearSelect(portType);
        clearSelect(stateBoard);
        clearSelect(port);
        clearSelect(multiplePorts);

        handleRole();
    });

    /*
    |--------------------------------------------------------------------------
    | Port Type Change Event
    |--------------------------------------------------------------------------
    */

    portType.on("change", function () {
        /*
        | Prevent recursive processing for ALL.
        */
        if (isAllAccess()) {
            return;
        }

        handlePortType();
    });

    /*
    |--------------------------------------------------------------------------
    | State Board Change Event
    |--------------------------------------------------------------------------
    */

    stateBoard.on("change", function () {
        /*
        | Do not process disabled/irrelevant State Board.
        */
        if (isAllAccess() || (!isPortAccess() && !isStateBoardAccess())) {
            return;
        }

        /*
        | User manually changed State Board.
        | Existing selected port values should no longer
        | be restored.
        */
        if (stateBoard.val() !== selectedStateBoard.val()) {
            selectedPort.val("");
            selectedPorts.val("");
        }

        handleStateBoard();
    });

    /*
    |--------------------------------------------------------------------------
    | Edit Mode
    |--------------------------------------------------------------------------
    */

    function initializeEditMode() {
        const currentRoleScope = getRoleAccessScope();

        /*
        |--------------------------------------------------------------------------
        | ALL
        |--------------------------------------------------------------------------
        */

        if (currentRoleScope === ACCESS_ALL) {
            handleAllAccess();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | STATE BOARD
        |--------------------------------------------------------------------------
        */

        if (currentRoleScope === ACCESS_STATE_BOARD) {
            handleStateBoardAccess();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | PORT
        |--------------------------------------------------------------------------
        */

        if (currentRoleScope === ACCESS_PORT) {
            /*
            | Port Type comes from employee.port_type_id.
            */
            const existingPortType = selectedPortTypeValue();

            if (existingPortType) {
                portType.val(existingPortType).trigger("change");
            }

            handlePortAccess();

            return;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Existing Port Type
    |--------------------------------------------------------------------------
    |
    | The hidden field is optional. If the form doesn't have it,
    | fall back to current select value.
    |
    */

    function selectedPortTypeValue() {
        const hiddenValue = $("#selected_port_type").val();

        if (hiddenValue !== undefined) {
            return hiddenValue;
        }

        return portType.val() || "";
    }

    /*
    |--------------------------------------------------------------------------
    | Form Initialization
    |--------------------------------------------------------------------------
    */

    function initializeForm() {
        hideAllPortSections();

        enableAllPortSections();

        /*
        |--------------------------------------------------------------------------
        | Create / Edit Initial Role Flow
        |--------------------------------------------------------------------------
        */

        if (!role.val()) {
            disableAllPortSections();

            return;
        }

        /*
        | Important:
        | Don't clear hidden selected values here.
        | They are required for edit-mode restoration.
        */
        handleRole();
    }

    /*
    |--------------------------------------------------------------------------
    | Initial Load
    |--------------------------------------------------------------------------
    */

    initializeForm();
});
