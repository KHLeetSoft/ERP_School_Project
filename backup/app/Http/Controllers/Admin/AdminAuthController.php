<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Auth\AdminLoginController;

class AdminAuthController extends AdminLoginController
{
    // This controller extends AdminLoginController to maintain compatibility
    // with existing routes that reference AdminAuthController
}
