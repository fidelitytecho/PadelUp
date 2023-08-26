<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * Load Court Details
     *
     * @return BelongsTo
     */
    public function Court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    /**
     * Load Service Details
     *
     * @return BelongsTo
     */
    public function Service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Load Currency Details
     *
     * @return BelongsTo
     */
    public function Currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Load Customer Details
     *
     * @return BelongsTo
     */
    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function Player(): BelongsToMany
    {
        // return $this->hasMany(Players::class, 'booking_id');
        return $this->belongsToMany(User::class, 'players');
    }

    /**
     * Load Category Details
     *
     * @return HasOneThrough
     */
    public function Category(): HasOneThrough
    {
        return $this->hasOneThrough(Category::class, Court::class, 'currency_id');
    }

    /**
     * Load Booking Payments
     *
     * @return HasMany
     */
    public function Payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    /**
     * Load Category Services
     *
     * @return HasManyThrough
     */
    public function Purchases(): HasManyThrough
    {
        return $this->hasManyThrough(
            Purchase::class,
            Payment::class,
            'booking_id', 'payment_id', 'id', 'id');
    }

    /**
     * Scope where Not Cancelled, Expired Or Failed
     *
     * @param $query
     * @return mixed
     */
    public function scopeWhereNotCancelled($query)
    {
        return $query->whereNotIn('label', ['Cancelled', 'Expired', 'Failed']);
    }

    /**
     * Scope where Cancelled, Expired Or Failed
     *
     * @param $query
     * @return mixed
     */
    public function scopeWhereCancelled($query)
    {
        return $query->whereIn('label', ['Cancelled', 'Expired', 'Failed']);
    }

    /**
     * Scope where End Date Larger than Now
     *
     * @param $query
     * @return mixed
     */
    public function scopeWhereUpcoming($query)
    {
        return $query->where('end_time', '>', date('Y-m-d H:i:s'));
    }

    /**
     * Scope where End Date Smaller than Now
     *
     * @param $query
     * @return mixed
     */
    public function scopeWherePrevious($query)
    {
        return $query->where('end_time', '<', date('Y-m-d H:i:s'));
    }
}
