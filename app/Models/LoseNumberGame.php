<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoseNumberGame extends Model
{
    use HasFactory;

    protected $table = 'lose_number_games';

    protected $fillable = [
        'lose_number',
        'timer',
        'start',
        'end',
        'active',
        'image',
    ];

    public function prices()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'loseNumber');
    }

    public function basicPrice()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'loseNumber')
            ->where('basic', 1)->first();
    }


    //***************************************************************

    public function scopeCurrentLoseNumberGame()
    {
        return $this->where('start', '<=', Carbon::now())
            ->where('end', '>=', Carbon::now())
            ->where('active', 1);
    }

    public function currentPrice()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'loseNumber')
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->where('active', 1)
            ->first();
    }
}
