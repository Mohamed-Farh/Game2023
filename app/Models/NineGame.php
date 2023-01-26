<?php

namespace App\Models;

use Carbon\Carbon;
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

    //***************************************************************

    public function scopeCurrentNineGame()
    {
        return $this->where('start', '<=', Carbon::now())
            ->where('end', '>=', Carbon::now())
            ->where('active', 1);
    }

    public function currentPrice()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'nine')
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->where('active', 1)
            ->first();
    }
}
