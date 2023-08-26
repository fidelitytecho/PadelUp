<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    public $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['email_verified_at', 'remember_token', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Bcrypt any Password
     *
     * @param $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Return User Full Name
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * Load Company Details
     *
     * @return HasOne
     */
    public function Company(): HasOne
    {
        return $this->hasOne(Company::class, 'company_id');
    }

    /**
     * Load Customer Details
     *
     * @return HasOne
     */
    public function Customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function Skill(): HasMany
    {
        return $this->hasMany(Skill::class, 'user_id');
    }

    public function Endorse(): HasMany
    {
        return $this->hasMany(Skill::class, 'endorser_id');
    }

    public function Notfication(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function Promo(): HasOne
    {
        return $this->hasOne(Promo::class, 'user_id');
    }

    public function Player(): HasMany
    {
        return $this->hasMany(Players::class, 'customer_id')
        ;
    }
}
