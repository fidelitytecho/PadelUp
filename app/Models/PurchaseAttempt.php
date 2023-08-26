<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseAttempt extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Load Purchase
     *
     * @return BelongsTo
     */
    public function Purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
