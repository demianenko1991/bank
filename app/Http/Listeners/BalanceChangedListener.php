<?php

namespace App\Http\Listeners;

use App\Http\Events\BalanceChangedEvent;
use App\Models\UserTransaction;

/**
 * Class BalanceChangedListener
 * @package App\Http\Listeners
 */
class BalanceChangedListener
{
    public function handle(BalanceChangedEvent $event)
    {
        $transaction = new UserTransaction();
        $transaction->makeTransaction($event->card->id, $event->amount, $event->message);
    }
}
