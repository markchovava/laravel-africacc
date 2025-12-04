<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'opportunity_id',
        'image',
        'created_at',
        'updated_at',
    ];


    public function opportunity() {
        return $this->belongsTo(Opportunity::class, 'opportunity_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
