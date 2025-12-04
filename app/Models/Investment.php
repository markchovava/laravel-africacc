<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'opportunity_id',
        'user_id',
        'status',
        'name',
        'email',
        'address',
        'phone',
        'country',
        'company_name',
        'profession',
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function opportunity(){
        return $this->belongsTo(Opportunity::class, 'opportunity_id', 'id');
    }
}
