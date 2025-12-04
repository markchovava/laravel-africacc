<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'priority',
        'name',
        'slug',
        'invest_link',
        'description',
        'portrait',
        'landscape',
        'updated_at',
        'created_at',
    ];
    

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function opportunities(){
        return $this->hasMany(Opportunity::class, 'country_id', 'id');
    }



}
