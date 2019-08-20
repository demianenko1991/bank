<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;

class AccountController extends Controller
{
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('account.profile', [
            'user' => $user,
            'cards' => $user->getNewestCards(2),
            'transactions' => $user->getLastTransactions(5),
        ]);
    }
}
