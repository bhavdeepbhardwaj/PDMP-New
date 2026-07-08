/*
|--------------------------------------------------------------------------
| Employee Form
|--------------------------------------------------------------------------
|
| Employee Create / Edit
| Role Based Access
| Major / Non Major Ports
| Multiple Port Assignment
|
*/

$(function () {
    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */

    const PORT_TYPE_MAJOR = 1;

    const PORT_TYPE_NON_MAJOR = 2;

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

    const state = $("#state_id");

    /*
    |--------------------------------------------------------------------------
    | Wrappers
    |--------------------------------------------------------------------------
    */

    const portTypeWrapper = $("#port-type-wrapper");

    const stateBoardWrapper = $("#state-board-wrapper");

    const portWrapper = $("#port-wrapper");

    const multiplePortWrapper = $("#multiple-port-wrapper");

    /*
    |--------------------------------------------------------------------------
    | Hidden Values (Edit Mode)
    |--------------------------------------------------------------------------
    */

    const selectedStateBoard = $("#selected_state_board").val();

    const selectedPort = $("#selected_port").val();

    const selectedPorts = $("#selected_ports").val();

    /*
    |--------------------------------------------------------------------------
    | Select2
    |--------------------------------------------------------------------------
    */

    if (multiplePorts.length) {
        multiplePorts.select2({
            width: "100%",

            placeholder: "Select Assigned Ports",

            allowClear: true,
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Functions
    |--------------------------------------------------------------------------
    */

    function resetStateBoard() {
        stateBoard.html('<option value="">Select State Board</option>');

        stateBoard.val("");
    }

    function resetPort() {
        port.html('<option value="">Select Port</option>');

        port.val("");
    }

    function resetMultiplePorts() {
        multiplePorts

            .html("")

            .val(null)

            .trigger("change");
    }

    /*
    |--------------------------------------------------------------------------
    | Wrapper Helpers
    |--------------------------------------------------------------------------
    */

    function showPortType() {
        portTypeWrapper.show();
    }

    function hidePortType() {
        portTypeWrapper.hide();
    }

    function showStateBoard() {
        stateBoardWrapper.show();
    }

    function hideStateBoard() {
        stateBoardWrapper.hide();
    }

    function showPort() {
        portWrapper.show();
    }

    function hidePort() {
        portWrapper.hide();
    }

    function showMultiplePorts() {
        multiplePortWrapper.show();
    }

    function hideMultiplePorts() {
        multiplePortWrapper.hide();
    }

    /*
    |--------------------------------------------------------------------------
    | Form Reset Helpers
    |--------------------------------------------------------------------------
    */

    function clearPortSelection() {
        portType.val("");

        resetStateBoard();

        resetPort();

        resetMultiplePorts();
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    function getSelectedRole() {
        return role.find(":selected");
    }

    function getAccessScope() {
        return getSelectedRole().data("access-scope");
    }

    function getAssignmentType() {
        return getSelectedRole().data("assignment-type");
    }

    /*
    |--------------------------------------------------------------------------
    | Next Part
    |--------------------------------------------------------------------------
    |
    | Part-2
    |
    | loadStateBoards()
    | loadPortsByCategory()
    | loadPortsByStateBoard()
    | loadMultiplePortsByStateBoard()
    |
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Master URL
    |--------------------------------------------------------------------------
    */

    const MASTER_URL = "/ajax/master";

    /*
    |--------------------------------------------------------------------------
    | AJAX Helper
    |--------------------------------------------------------------------------
    */

    function ajaxGet(url, callback = null) {
        $.ajax({
            url: url,

            type: "GET",

            dataType: "json",

            success: function (response) {
                if (!response.status) {
                    return;
                }

                if (callback) {
                    callback(response.data);
                }
            },

            error: function (xhr) {
                console.error(xhr);
            },
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Load State Boards
    |--------------------------------------------------------------------------
    */

    function loadStateBoards(callback = null) {
        resetStateBoard();

        ajaxGet(
            MASTER_URL + "/state-boards",

            function (data) {
                $.each(data, function (_, item) {
                    stateBoard.append(
                        `<option value="${item.id}">
                            ${item.board_name}
                        </option>`,
                    );
                });

                if (selectedStateBoard) {
                    stateBoard.val(selectedStateBoard);
                }

                if (callback) {
                    callback();
                }
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Load Major Ports
    |--------------------------------------------------------------------------
    */

    function loadPortsByCategory(callback = null) {
        resetPort();

        ajaxGet(
            MASTER_URL + "/port-categories/" + portType.val() + "/ports",

            function (data) {
                $.each(data, function (_, item) {
                    port.append(
                        `<option value="${item.id}">
                            ${item.port_name}
                        </option>`,
                    );
                });

                if (selectedPort) {
                    port.val(selectedPort);
                }

                if (callback) {
                    callback();
                }
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Load Single Port
    |--------------------------------------------------------------------------
    */

    function loadPortsByStateBoard(callback = null) {
        resetPort();

        ajaxGet(
            MASTER_URL + "/state-boards/" + stateBoard.val() + "/ports",

            function (data) {
                $.each(data, function (_, item) {
                    port.append(
                        `<option value="${item.id}">
                            ${item.port_name}
                        </option>`,
                    );
                });

                if (selectedPort) {
                    port.val(selectedPort);
                }

                if (callback) {
                    callback();
                }
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Load Multiple Ports
    |--------------------------------------------------------------------------
    */

    function loadMultiplePortsByStateBoard(callback = null) {
        resetMultiplePorts();

        ajaxGet(
            MASTER_URL + "/state-boards/" + stateBoard.val() + "/ports",

            function (data) {
                $.each(data, function (_, item) {
                    multiplePorts.append(
                        `<option value="${item.id}">
                            ${item.port_name}
                        </option>`,
                    );
                });

                if (selectedPorts && selectedPorts.length) {
                    multiplePorts.val(selectedPorts.split(","));
                }

                multiplePorts.trigger("change");

                if (callback) {
                    callback();
                }
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Next Part
    |--------------------------------------------------------------------------
    |
    | Part-3
    |
    | handleRole()
    | handleAllAccess()
    | handlePortAccess()
    | handleStateBoardAccess()
    | Port Type Flow
    |
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Role Handlers
    |--------------------------------------------------------------------------
    */

    function handleAllAccess() {
        hidePortType();

        hideStateBoard();

        hidePort();

        hideMultiplePorts();

        clearPortSelection();
    }

    function handleStateBoardAccess() {
        showPortType();

        showStateBoard();

        hidePort();

        showMultiplePorts();

        portType.val(PORT_TYPE_NON_MAJOR).trigger("change");
    }

    function handlePortAccess() {
        showPortType();

        showPort();

        hideMultiplePorts();

        resetMultiplePorts();

        portType.trigger("change");
    }

    /*
    |--------------------------------------------------------------------------
    | Role Dispatcher
    |--------------------------------------------------------------------------
    */

    function handleRole() {
        switch (getAccessScope()) {
            case ACCESS_ALL:
                handleAllAccess();

                break;

            case ACCESS_STATE_BOARD:
                handleStateBoardAccess();

                break;

            case ACCESS_PORT:

            default:
                handlePortAccess();

                break;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Port Type Handler
    |--------------------------------------------------------------------------
    */

    function handlePortType() {
        resetPort();

        resetStateBoard();

        resetMultiplePorts();

        hideMultiplePorts();

        if (Number(portType.val()) === PORT_TYPE_MAJOR) {
            hideStateBoard();

            showPort();

            loadPortsByCategory();

            return;
        }

        if (Number(portType.val()) === PORT_TYPE_NON_MAJOR) {
            showStateBoard();

            if (getAssignmentType() === ASSIGNMENT_MULTIPLE) {
                hidePort();
            } else {
                showPort();
            }

            loadStateBoards();

            return;
        }

        hideStateBoard();

        hidePort();
    }

    /*
    |--------------------------------------------------------------------------
    | State Board Handler
    |--------------------------------------------------------------------------
    */

    function handleStateBoard() {
        if (!stateBoard.val()) {
            resetPort();

            resetMultiplePorts();

            return;
        }

        if (getAssignmentType() === ASSIGNMENT_MULTIPLE) {
            showMultiplePorts();

            hidePort();

            loadMultiplePortsByStateBoard();

            return;
        }

        hideMultiplePorts();

        showPort();

        loadPortsByStateBoard();
    }

    /*
    |--------------------------------------------------------------------------
    | Next Part
    |--------------------------------------------------------------------------
    |
    | Part-4
    |
    | Event Bindings
    | Edit Mode
    | Initialization
    |
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Event Bindings
    |--------------------------------------------------------------------------
    */

    role.on("change", function () {
        clearPortSelection();

        handleRole();
    });

    portType.on("change", function () {
        handlePortType();
    });

    stateBoard.on("change", function () {
        handleStateBoard();
    });

    /*
    |--------------------------------------------------------------------------
    | Edit Mode
    |--------------------------------------------------------------------------
    */

    function initializeEditMode() {
        /*
        |--------------------------------------------------------------------------
        | Selected State Board
        |--------------------------------------------------------------------------
        */

        if (
            Number(portType.val()) === PORT_TYPE_NON_MAJOR &&
            selectedStateBoard
        ) {
            loadStateBoards(function () {
                stateBoard.val(selectedStateBoard);

                /*
                |--------------------------------------------------------------------------
                | Multiple Port Assignment
                |--------------------------------------------------------------------------
                */

                if (getAssignmentType() === ASSIGNMENT_MULTIPLE) {
                    loadMultiplePortsByStateBoard();
                } else {

                /*
                |--------------------------------------------------------------------------
                | Single Port Assignment
                |--------------------------------------------------------------------------
                */
                    loadPortsByStateBoard();
                }
            });
        } else if (Number(portType.val()) === PORT_TYPE_MAJOR) {

        /*
        |--------------------------------------------------------------------------
        | Major Port
        |--------------------------------------------------------------------------
        */
            loadPortsByCategory();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Initial Page Load
    |--------------------------------------------------------------------------
    */

    function initializeForm() {
        /*
        |--------------------------------------------------------------------------
        | Hide Optional Sections
        |--------------------------------------------------------------------------
        */

        hideStateBoard();

        hideMultiplePorts();

        /*
        |--------------------------------------------------------------------------
        | Role Based UI
        |--------------------------------------------------------------------------
        */

        handleRole();

        /*
        |--------------------------------------------------------------------------
        | Edit Mode
        |--------------------------------------------------------------------------
        */

        initializeEditMode();
    }

    /*
    |--------------------------------------------------------------------------
    | Initialize
    |--------------------------------------------------------------------------
    */

    initializeForm();
});
