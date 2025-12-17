<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['deal_id','contact_id','user_id','created_by_id','type','subject','due_at','done'];
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by_id');
    }
}
