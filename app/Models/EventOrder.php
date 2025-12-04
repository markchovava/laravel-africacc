<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'event_id',
        'name',
        'address',
        'company_name',
        'country',
        'email',
        'status',
        'is_agree',
        'payment_method',
        'phone',
        'profession',
        'joining_fee',
        'event_total',
        'number_of_people',
        'created_at',
        'updated_at',
    ];

    public function event(){
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
