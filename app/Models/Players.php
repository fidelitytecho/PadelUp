<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Players extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Summary of Wallet
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
