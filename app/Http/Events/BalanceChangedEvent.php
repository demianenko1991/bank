<?php

namespace App\Http\Events;

use App\Models\UserCard;

/**
 * Class BalanceChangedEvent
 * @package App\Http\Events
 */
class BalanceChangedEvent
{
    
    /**
     * @var float
     */
    public $amount;
    
    /**
     * @var UserCard
     */
    public $card;
    
    /**
     * @var string
     */
    public $message;
    
    /**
     * BalanceChangedEvent constructor.
     *
     * @param UserCard $card
     * @param float $amount
     * @param UserCard|null $opponent
     * @param bool $replenish
     */
    public function __construct(UserCard $card, float $amount, ?UserCard $opponent = null, bool $replenish = true)
    {
        $this->card = $card;
        $this->amount = $amount;
        $this->message = $this->getMessage($opponent, $replenish);
    }
    
    /**
     * @param UserCard|null $opponent
     * @param bool $replenish
     * @return string
     */
    public function getMessage(?UserCard $opponent, bool $replenish): string
    {
        if (is_null($opponent)) {
            if ($replenish === true) {
                return 'Счет пополнен вручную';
            }
            return 'Ручное списание средств';
        }
        if ($replenish === true) {
            return "Перевод с карты $opponent->hidden_card_number ({$opponent->cardHolder->name})";
        }
        return "Перевод на карту $opponent->hidden_card_number ({$opponent->cardHolder->name})";
    }
    
}
