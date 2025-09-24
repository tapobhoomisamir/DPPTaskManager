<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function noAccess()
    {
        return view('errors/no_access');
    }
}