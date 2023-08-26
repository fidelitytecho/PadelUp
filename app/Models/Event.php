<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Load Court Details
     *
     * @return BelongsTo
     */
    public function Court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'court_id');
    }
}
