<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['attachable_type','attachable_id','path','original_name','size'];
}
