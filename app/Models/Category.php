<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Load Category Services
     *
     * @return HasManyThrough
     */
    public function Services(): HasManyThrough
    {
        return $this->hasManyThrough(
            Service::class,
            CategoryService::class,
            'category_id', 'id', 'id', 'service_id')->orderBy('duration');
    }

    /**
     * Load Category Courts
     *
     * @return HasMany
     */
    public function Courts(): HasMany
    {
        return $this->hasMany(Court::class, 'category_id');
    }

    /**
     * Load Category Images
     *
     * @return HasMany
     */
    public function Images(): HasMany
    {
        return $this->hasMany(CategoryImage::class, 'category_id');
    }

    /**
     * Load Category Reviews
     *
     * @return HasMany
     */
    public function Reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'category_id');
    }

    /**
     * Load Company Details
     *
     * @return BelongsTo
     */
    public function Company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
