<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Load Customer Details
     *
     * @return BelongsTo
     */
    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
