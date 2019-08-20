<?php

namespace App\Http\Controllers;

use Auth;

class AccountController extends Controller
{
    public function profile()
    {
        return view('account.profile', [
            'user' => Auth::user(),
        ]);
    }
}
