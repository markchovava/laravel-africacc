<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'slug',
        'description',
        'landscape',
        'portrait',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function opportunities(){
        return $this->belongsToMany(Opportunity::class, 'opportunity_sectors', 'sector_id', 'opportunity_id')
            ->withTimestamps();
    }


}
