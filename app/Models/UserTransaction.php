<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserTransaction
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $creation_date
 * @property float $amount
 * @property int $card_id
 * @property string $message
 * @property-read UserCard $card
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTransaction query()
 * @mixin \Eloquent
 */
class UserTransaction extends Model
{
    protected $fillable = ['amount', 'card_id', 'message'];
    
    public function card()
    {
        return $this->belongsTo(UserCard::class, 'card_id', 'id');
    }
    
    public function getCreationDateAttribute()
    {
        return $this->created_at->format('Y.m.d H:i');
    }
    
    /**
     * @param int $cardId
     * @param int $amount
     * @param string $message
     * @return bool
     */
    public function makeTransaction(int $cardId, int $amount, string $message)
    {
        $this->card_id = $cardId;
        $this->amount = $amount;
        $this->message = $message;
        
        return $this->save();
    }
}
