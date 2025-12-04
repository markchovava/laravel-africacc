<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'description',
        'location',
        'date',
        'duration',
        'joining_fee',
        'priority',
        'slug',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_events', 'event_id', 'user_id')
            ->withTimestamps();
    }


}
