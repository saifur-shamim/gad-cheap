<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    //  A user can have many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    //  A user can have many coupons
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    //  A user belongs to a badge (silver/gold/vip)
    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    //  A user can refer many users
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }
}
