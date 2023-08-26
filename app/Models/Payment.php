<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Load Payments Purchases
     *
     * @return HasMany
     */
    public function Purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'payment_id');
    }

    /**
     * Load Payment Mode
     *
     * @return BelongsTo
     */
    public function PaymentMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode');
    }

    /**
     * Load Booking
     *
     * @return BelongsTo
     */
    public function Booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
