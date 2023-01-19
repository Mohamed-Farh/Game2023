<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LamaLama\Wishlist\HasWishlists;
use Laravel\Sanctum\HasApiTokens;
use Mindscms\Entrust\Traits\EntrustUserWithPermissionsTrait;
use Nicolaslopezj\Searchable\SearchableTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,SearchableTrait ,EntrustUserWithPermissionsTrait;
    use HasWishlists;

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'mobile',
        'otp_code',
        'mobile_verify',
        'password',
        'token_amount',
        'user_image',
        'active',
        'receive_emails',
        'account_status',
    ];

    protected $appends = ['full_name'];

    protected $searchable = [
        'columns' => [
            'users.first_name' => 10,
            'users.last_name' => 10,
            'users.username' => 10,
            'users.email' => 10,
        ],
    ];



    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notification()
    {
        return $this->MorphMany(Notification::class, 'notifiable');
    }
    public function getFullNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function status(): string
    {
        return $this->active ? 'نشط' : 'غير نشط';
    }
    public function exportTransitions()
    {
        return $this->hasMany(Transition::class,'sender_id');
    }
    public function importTransitions()
    {
        return $this->hasMany(Transition::class,'receiver_id');
    }
    public function winThisGameBefore(): HasMany
    {
        return $this->hasMany(Price::class, 'user_id', 'id');
    }

    public function playerPrices(): HasMany
    {
        return $this->hasMany(PlayerPrice::class, 'user_id', 'id');
    }



    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'user_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function cartProducts(): HasMany
    {
        return $this->hasMany(CartProduct::class, 'user_id', 'id')->latest();
    }
    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'product_id', 'id');
    }
    public function maxLimit()
    {
        return $this->belongsTo(UserMaxLimit::class);
    }
    public function getMaxLimit()
    {
//        return $this->belongsTo(UserMaxLimit::class)->select(['max_limmit']);
        UserMaxLimit::query()
            ->with([ 'maxLimit' => function ($query) {
                $query->select('max_limmit');
            }])
            ->first();
    }

//    public function scopeGeMaxLimit($query)
//    {
//        $query->whereHas('maxLimit', function ($q) {
//            $q->pluck('max_limit');
//        });
//    }
    //Return product that still in shopping cart Before doing order of this products
//    public function shoppingCartProducts(): HasMany
//    {
//        return $this->hasMany(CartProduct::class, 'user_id', 'id')->where('status', 'shopping_cart');
//    }

    public function pendingOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id')
            ->where('status', 'pending')
            ->where('customer_status', 'waiting')
            ->latest();
    }
    public function completedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id')
            ->where('status', 'completed')
            ->where('customer_status', 'waiting')
            ->latest();
    }
    public function completedInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id', 'id')
            ->where('status', 'completed')
            ->where('paid', true)
            ->latest();
    }
    public function mustBePaid(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id')
            ->where('status', 'pending')
            ->where('customer_status', 'waiting')
            ->where('paid', false)
            ->latest();
    }
    public function haveBeenPaid(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id')
            ->where('status', 'pending')
            ->where('customer_status', 'waiting')
            ->where('paid', true)
            ->latest();
    }
}
