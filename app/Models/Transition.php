<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nicolaslopezj\Searchable\SearchableTrait;

class Transition extends Model
{
    use HasFactory, SearchableTrait;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'amount',
        'complete',
    ];

    protected $searchable = [
        'columns' => [
            'transitions.amount' => 10,
            'transitions.complete' => 10,

            'users.first_name'  => 10,
            'users.last_name'   => 10,
            'users.username'    => 10,
        ],

        'joins' =>[
            'users'     => ['users.id',      'transitions.sender_id'],
            'users'     => ['users.id',      'transitions.receiver_id'],
        ]
    ];

    public function senderPlayer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
    public function receiverPlayer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }

}
