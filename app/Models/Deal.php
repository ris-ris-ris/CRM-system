<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Stage;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id','contact_id','stage_id','user_id','title','amount','currency','expected_close_date','description'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function company() {
        return $this->belongsTo(Company::class);
    }
    
    public function contact() {
        return $this->belongsTo(Contact::class);
    }
    
    public function stage() {
        return $this->belongsTo(Stage::class);
    }
    
    public function emails() {
        return $this->morphMany(EmailRelation::class, 'related');
    }
}
