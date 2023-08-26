<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Court extends Model
{
    use HasFactory;

    /**
     * Load Category Services
     *
     * @return HasManyThrough
     */
    protected $guarded = [];
    public function Services(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            CourtService::class,
            'court_id', 'id', 'id', 'service_id');
    }
}
