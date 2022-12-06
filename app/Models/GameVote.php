<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameVote extends Model
{
    use HasFactory;

    protected $table = 'game_votes';


    protected $fillable = [
        'user_id',
        'game_id',
        'game_type',
        'price_id',
        'numbers',
        'vote',
        'active',
    ];
    protected $casts = ['numbers' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
