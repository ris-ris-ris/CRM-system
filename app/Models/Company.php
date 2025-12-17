<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use App\Models\Deal;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'website', 'industry', 'city', 'country', 'address'
    ];
    
    public function contacts() {
        return $this->hasMany(Contact::class);
    }
    
    public function deals() {
        return $this->hasMany(Deal::class);
    }
    
    public function emails() {
        return $this->morphMany(EmailRelation::class, 'related');
    }
}
