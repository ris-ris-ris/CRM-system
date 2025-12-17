<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    protected $fillable = [
        'email_id', 'filename', 'path', 'size', 'mime_type'
    ];
    
    public function email() {
        return $this->belongsTo(Email::class);
    }
}
