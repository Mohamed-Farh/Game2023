<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'game_type',
        'name',
        'description',
        'value',
        'code',
        'start_time',
        'end_time',
        'win_tokens',
        'active',
        'basic',
        'image',
    ];

    public function notification()
    {
        return $this->MorphMany(Notification::class, 'notifiable');
    }

    public function hundredGame(): BelongsTo
    {
        return $this->belongsTo(HundredGame::class, 'game_id', 'id');
    }

    public function nineGame()
    {
        return $this->belongsTo(NineGame::class, 'game_id', 'id');
    }

    public function loseNumberGame(): BelongsTo
    {
        return $this->belongsTo(LoseNumberGame::class, 'game_id', 'id');
    }

    public function playerPrices(): HasMany
    {
        return $this->hasMany(PlayerPrice::class, 'price_id', 'id');
    }

}
