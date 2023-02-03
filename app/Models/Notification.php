<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $table = 'notifications';

//    protected $guarded = [];
    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'content',
        'icon',
        'read_at',
        'created_at',
        'updated_at',
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
