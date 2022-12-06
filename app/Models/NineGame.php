<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NineGame extends Model
{
    use HasFactory;

    protected $table = 'nine_games';

    protected $fillable = [
        'no_of_win_numbers',
        'win_numbers',
        'timer',
        'start',
        'end',
        'active',
        'image',
    ];
    protected $casts = ['win_numbers' => 'array'];

    public function prices()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'nine');
    }

    public function basicPrice()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'nine')
            ->where('basic', 1)->first();
    }
}
