<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $guarded = [];
    protected $table = 'notifications';

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
