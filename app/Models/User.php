<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'gender',
        'password',
        'role_level',
        'email',
        'phone',
        'address',
        'country',
        'code',
        'image',
        'company_name',
        'profession',
        'created_at',
        'updated_at',
    ];


    public function qrcode(){
        return $this->hasOne(QrCode::class, 'user_id', 'id');
    }

    public function opportunities(){
        return $this->belongsToMany(Opportunity::class, 'opportunity_sectors', 'sector_id', 'opportunity_id')
            ->withTimestamps();
    }

    public function events(){
        return $this->belongsToMany(Event::class, 'user_events', 'user_id', 'event_id')
            ->withTimestamps();
    }

    public function membership(){
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_level', 'level');
    }
   


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
