<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops';

    protected $fillable = [
        'name',
        'win_tokens',
        'cost',
        'code',
        'start_time',
        'end_time',
        'active',
        'free',
        'image',
    ];

    public function notification()
    {
        return $this->MorphMany(Notification::class, 'notifiable');
    }

}
