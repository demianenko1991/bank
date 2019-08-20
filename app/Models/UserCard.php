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
 * @property string $number
 * @property-read string $hidden_card_number
 * @property int $cvv
 * @property float $balance
 * @property boolean $state
 * @property int $user_id
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
    
    protected $fillable = ['number', 'expires_at', 'cvv', 'user_id'];
    
    protected $dates = ['expires_at'];
    
    protected $casts = ['state' => 'boolean'];
    
    public function getHiddenCardNumberAttribute()
    {
        return "**** " . Str::substr($this->number, 12, 4);
    }
    
    public function cardHolder()
    {
        return $this->belongsTo(User::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(UserTransaction::class, 'card_id', 'id');
    }
}
