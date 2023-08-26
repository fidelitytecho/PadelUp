<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Load Company Details
     *
     * @return BelongsTo
     */
    public function Currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
