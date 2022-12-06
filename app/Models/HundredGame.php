<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HundredGame extends Model
{
    use HasFactory;

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
            ->where('game_type', 'hundred');
    }

    public function basicPrice()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'hundred')
            ->where('basic', 1)->first();
    }

    //***************************************************************

    public function scopeCurrentHundredGame()
    {
        return $this->where('start', '<=', Carbon::now())
            ->where('end', '>=', Carbon::now())
            ->where('active', 1);
    }

    public function currentPrice()
    {
        return $this->hasMany(Price::class,'game_id')
            ->where('game_type', 'hundred')
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->where('active', 1)
            ->first();
    }


 }
