<?php

namespace App\Http\Controllers;

use App\Http\Events\BalanceChangedEvent;
use App\Models\User;
use App\Models\UserCard;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('transactions.index', [
            'user' => $user,
            'transactions' => $user->transactions()->paginate(5),
            'cardsDictionary' => $user->cards->mapWithKeys(function (UserCard $card) {
                return [$card->id => "$card->card_number ($card->balance$)"];
            })->toArray(),
        ]);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        // Simple validation
        $this->validate($request, [
            'amount' => 'required|integer|min:1',
            'pin' => 'required|string|size:4',
            'custom_number' => 'required_if:card_to,0|nullable|string|size:16',
        ]);
        $cardToId = (int)$request->input('card_to');
        $cardFromId = (int)$request->input('card_from');
        // Check if cards are with equal ID
        if ($cardFromId === $cardToId) {
            throw ValidationException::withMessages([
                'card_from' => 'Cards can not be equal',
            ]);
        }
        $cardFrom = UserCard::find($cardFromId);
        // Check if card blocked or card holder is not current user
        if (!$cardFrom || $cardFrom->blocked() || $cardFrom->user_id !== Auth::id()) {
            throw ValidationException::withMessages([
                'card_from' => 'Please choose from your cards',
            ]);
        }
        $amount = $request->input('amount');
        // Check for enough money on the card balance
        if ($amount > $cardFrom->balance) {
            throw ValidationException::withMessages([
                'card_from' => 'You have not enough money on your balance!',
            ]);
        }
        // Check pin for correctness
        if ($cardFrom->pin !== $request->input('pin')) {
            throw ValidationException::withMessages([
                'pin' => 'Pin is incorrect',
            ]);
        }
        // Get card instance where we should transfer money
        if ($cardToId !== 0) {
            $cardTo = UserCard::find($cardToId);
        } else {
            $cardTo = UserCard::where('number', $request->input('custom_number'))->first();
        }
        if (!$cardTo) {
            throw ValidationException::withMessages([
                'custom_number' => 'Card does not exist in the system',
            ]);
        }
        // Make transactions and write them to log
        \DB::transaction(function () use ($cardFrom, $cardTo, $amount) {
            if($cardFrom->withdraw($amount) === false) {
                return;
            }
            event(new BalanceChangedEvent(
                $cardFrom,
                -$amount,
                $cardTo,
                false
            ));
            if($cardTo->replenish($amount) === false) {
                return;
            }
            event(new BalanceChangedEvent(
                $cardTo,
                $amount,
                $cardFrom
            ));
        });
        return redirect()->route('transactions.index')
            ->with('successMessage', 'Transaction successfully created');
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('transactions.create', [
            'user' => $user,
            'cardsDictionary' => $user->cards->filter(function (UserCard $card) {
                return $card->blocked() === false;
            })->mapWithKeys(function (UserCard $card) {
                return [$card->id => "$card->card_number ($card->balance$)"];
            })->toArray(),
        ]);
    }
    
}
