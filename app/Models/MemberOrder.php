<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'member_id',
        'membership_id',
        'user_id',
        'member_fee',
        'paid_amount',
        'start_date',
        'duration',
        'end_date',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function member(){
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function membership(){
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }

    public function member_order_info(){
        return $this->hasOne(MemberOrderInfo::class, 'member_order_id', 'id');
    }


    
}
