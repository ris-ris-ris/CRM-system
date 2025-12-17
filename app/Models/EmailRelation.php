<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailRelation extends Model
{
    protected $fillable = [
        'email_id', 'related_type', 'related_id'
    ];
    
    public function email() {
        return $this->belongsTo(Email::class);
    }
    
    public function related() {
        return $this->morphTo();
    }
}
