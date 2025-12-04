<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'slug',
        'priority',
        'description',
        'short_description',
        'expected_return',
        'amount',
        'country_id',
        'updated_at',
        'created_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function investment(){
        return $this->hasOne(Investment::class, 'opportunity_id', 'id');
    }

    public function sectors(){
        return $this->belongsToMany(Sector::class, 'opportunity_sectors', 'opportunity_id', 'sector_id')
            ->withTimestamps();
    }

    public function opportunity_images(){
        return $this->hasMany(OpportunityImage::class, 'opportunity_id', 'id');
    }


}
