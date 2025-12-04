<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunitySector extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'opportunity_id',
        'sector_id',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function opportunity(){
        return $this->belongsTo(Opportunity::class, 'opportunity_id', 'id');
    }

    public function sector(){
        return $this->belongsTo(Sector::class, 'sector_id', 'id');
    }

}
