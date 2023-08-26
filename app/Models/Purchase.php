<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Load Booking Payments
     *
     * @return HasMany
     */
    public function PurchaseAttempt(): HasMany
    {
        return $this->hasMany(PurchaseAttempt::class, 'purchase_id');
    }

    /**
     * Load Payment
     *
     * @return BelongsTo
     */
    public function Payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Scope where Visa
     *
     * @param $query
     * @return mixed
     */
    public function scopeWhereVisa($query)
    {
        return $query->where('payment_mode_id', 3);
    }

    /**
     * Scope where Visa
     *
     * @param $query
     * @return mixed
     */
    public function scopeWhereCash($query)
    {
        return $query->where('payment_mode_id', 2);
    }

    /**
     * Scope where Visa
     *
     * @param $query
     * @return mixed
     */
    public function scopeWhereWallet($query)
    {
        return $query->where('payment_mode_id', 1);
    }
}
