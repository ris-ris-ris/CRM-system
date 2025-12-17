<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Deal;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'first_name', 'last_name', 'email', 'phone', 'position'
    ];
    
    public function company() {
        return $this->belongsTo(Company::class);
    }
    
    public function deals() {
        return $this->hasMany(Deal::class);
    }
    
    public function emails() {
        return $this->morphMany(EmailRelation::class, 'related');
    }
}
