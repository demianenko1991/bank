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
    protected $fillable = ['amount', 'from_card_id', 'to_card_id'];
    
    public function card()
    {
        return $this->belongsTo(UserCard::class, 'card_id', 'id');
    }
}
