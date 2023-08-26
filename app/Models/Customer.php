<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['created_at', 'updated_at'];


    /**
     * Load User Details
     *
     * @return BelongsTo
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Load Customer Bookings
     *
     * @return HasMany
     */
    public function Bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id')->latest();
    }

    /**
     * Load Customer Wallet
     *
     * @return HasMany
     */
    public function Wallet(): HasMany
    {
        return $this->hasMany(WalletHistory::class, 'customer_id')->latest();
    }
}
