<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'event_id',
        'joining_fee',
        'number_of_people',
        'event_total',
        'cart_token',
        'created_at',
        'updated_at',
    ];

    public function event(){
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
