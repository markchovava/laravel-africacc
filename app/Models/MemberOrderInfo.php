<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberOrderInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'member_order_id',
        'membership_id',
        'name',
        'phone',
        'country',
        'address',
        'website',
        'who_join',
        'email',
        'profession',
        'company_name',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function member_order(){
        return $this->belongsTo(MemberOrder::class, 'member_order_id', 'id');
    }

    public function membership(){
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }
    
}
