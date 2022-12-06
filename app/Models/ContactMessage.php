<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{

    use SearchableTrait;

    protected $table = 'contact_messages';

    protected $guarded = [];

    protected $searchable = [
        'columns' => [
            'contact_messages.full_name' => 10,
            'contact_messages.country' => 10,
            'contact_messages.company' => 10,
            'contact_messages.mobile' => 10,
            'contact_messages.status' => 10,
        ],
    ];


    public $timestamps = true;


    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

}
