<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\UserCard
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $expires_at
 * @property string $expiration_date
 * @property string $number
 * @property-read string $card_number
 * @property-read string $hidden_card_number
 * @property string $cvv
 * @property float $balance
 * @property int $state
 * @property int $user_id
 * @property string $pin
 * @property-read User $cardHolder
 * @property-read Collection|UserTransaction[] $transactions
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCard query()
 * @mixin \Eloquent
 */
class UserCard extends Model
{
    const STATE_BLOCKED = 0;
    const STATE_ACTIVE = 1;
    
    protected $fillable = ['number', 'expires_at', 'cvv', 'user_id', 'pin'];
    
    protected $dates = ['expires_at'];
    
    public function getHiddenCardNumberAttribute()
    {
        return "**** " . Str::substr($this->number, 12, 4);
    }
    
    public function replenish(int $amount): bool
    {
        $this->balance += $amount;
        return $this->save();
    }
    
    public function withdraw(int $amount): bool
    {
        return $this->replenish(-$amount);
    }
    
    public function getCardNumberAttribute()
    {
        return implode(' ', str_split($this->number, 4));
    }
    
    public function getExpirationDateAttribute()
    {
        return $this->expires_at->format('m / Y');
    }
    
    public function cardHolder()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function transactions()
    {
        return $this
            ->hasMany(UserTransaction::class, 'card_id', 'id')
            ->latest();
    }
    
    public function lastTransactions(int $limit = 5)
    {
        return $this->transactions()->limit($limit)->get();
    }
    
    public function blocked(): bool
    {
        return $this->state === static::STATE_BLOCKED;
    }
    
    public function triggerState(): bool
    {
        $this->state = $this->blocked() ? static::STATE_ACTIVE : static::STATE_BLOCKED;
        return $this->save();
    }
    
    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function createAndAttach(User $user)
    {
        $this->generateCardNumber();
        $this->generatePinCode();
        $this->generateCvv();
        $this->user_id = $user->id;
        $this->expires_at = Carbon::now()->addYears(5);
        $this->state = static::STATE_ACTIVE;
        
        return $this->save();
    }
    
    /**
     * @throws \Exception
     */
    protected function generateCardNumber()
    {
        $this->number = $this->generateRandomNumbers(16);
    }
    
    /**
     * @throws \Exception
     */
    protected function generatePinCode()
    {
        $this->pin = $this->generateRandomNumbers(4);
    }
    
    /**
     * @throws \Exception
     */
    protected function generateCvv()
    {
        $this->cvv = $this->generateRandomNumbers(3);
    }
    
    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    private function generateRandomNumbers(int $length): string
    {
        $numbers = '';
        for ($i = 1; $i <= $length; $i++) {
            $numbers .= random_int(0, 9);
        }
        return $numbers;
    }
}
