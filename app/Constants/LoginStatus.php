<?php

namespace App\Constants;

class LoginStatus
{
    public const SUCCESS = 'Login Successful';

    public const FAILED_EMPLOYEE = 'Failed - Employee Not Found';

    public const FAILED_PASSWORD = 'Failed - Wrong Password';

    public const FAILED_INACTIVE = 'Failed - Account Inactive';

    public const PASSWORD_RESET_SUCCESS = 'Password Reset Successful';
}
