<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GamePlayer extends Model
{
    use HasFactory;

    protected $table = 'game_players';


    protected $fillable = [
        'user_id',
        'game_id',
        'game_type',
        'price_id',
        'timer',
        'numbers',
        'play',
        'win',
        'active',
    ];

    protected $casts = ['numbers' => 'array'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function playerPrices(): HasMany
    {
        return $this->hasMany(PlayerPrice::class, 'game_player_id', 'id');
    }

    public function scopeWinGameBefore($game_id, $type)
    {
        return $this->where(function($query) use ($game_id, $type) {
                        $query->where('user_id', \auth()->id())
                            ->where('game_id', $game_id)
                            ->where('game_type', $type)
                            ->where('win', 1);
        });
    }
}
