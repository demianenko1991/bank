<?php

namespace App\Http\Controllers;

use App\Http\Events\BalanceChangedEvent;
use App\Models\User;
use App\Models\UserCard;
use Auth;
use Illuminate\Http\Request;

/**
 * Class CardController
 * @package App\Http\Controllers
 */
class CardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('cards.index', [
            'user' => $user,
            'cards' => $user->cards()->paginate(10),
        ]);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $card = new UserCard();
        if($card->createAndAttach(Auth::user())) {
            return redirect()
                ->route('cards.show', ['id' => $card->id])
                ->with('successMessage', 'New card created!');
        }
        return redirect()
            ->route('account.profile')
            ->with(
                'errorMessage',
                'Error occur. Please try again later'
            );
    }
    
    /**
     * @param UserCard $card
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(UserCard $card)
    {
        if ($card->user_id !== Auth::id()) {
            abort(403, 'Access denied!');
        }
        return view('cards.show', [
            'card' => $card,
            'transactions' => $card->lastTransactions(5),
        ]);
    }
    
    /**
     * @param Request $request
     * @param UserCard $card
     * @return \Illuminate\Http\RedirectResponse
     */
    public function block(Request $request, UserCard $card)
    {
        if ($card->user_id !== Auth::id()) {
            abort(403, 'Access denied!');
        }
        if ($card->triggerState()) {
            return redirect()->back()
                ->with('message', $card->blocked() ? 'Карта заблокирована' : 'Карта разблокирована');
        }
        return redirect()->back()->withErrors('Произошла ошибка!');
    }
    
    /**
     * @param Request $request
     * @param UserCard $card
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function replenish(Request $request, UserCard $card)
    {
        if ($card->user_id !== Auth::id()) {
            abort(403, 'Access denied!');
        }
        $this->validate($request, [
            'amount' => 'required|integer|not_in:0|min:' . floor($card->balance),
            'pin' => 'required|integer|in:' . $card->pin,
        ]);
        if ($card->blocked()) {
            return redirect()->back();
        }
        $amount = $request->input('amount');
        if($card->replenish($amount)) {
            event(new BalanceChangedEvent($card, abs($amount), null, $amount > 0));
        }
        return redirect()->back();
    }
}
