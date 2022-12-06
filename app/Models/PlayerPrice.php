<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPrice extends Model
{
    use HasFactory;

    protected $table = 'players_prices';


    protected $fillable = [
        'user_id',
        'game_player_id',
        'price_id',
        'send',
        'delivered',
        'active',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function gamePlayer(): BelongsTo
    {
        return $this->belongsTo(GamePlayer::class, 'game_player_id', 'id');
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }
}
