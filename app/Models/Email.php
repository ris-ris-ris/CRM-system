<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'email_account_id', 'message_id', 'thread_id',
        'from_email', 'from_name', 'to_email', 'to_name', 'cc', 'bcc',
        'subject', 'body_html', 'body_text',
        'is_read', 'is_starred', 'direction',
        'received_at', 'sent_at', 'attachments_count'
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
        'is_starred' => 'boolean',
        'received_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
    
    public function emailAccount() {
        return $this->belongsTo(EmailAccount::class);
    }
    
    public function attachments() {
        return $this->hasMany(EmailAttachment::class);
    }
    
    public function relations() {
        return $this->hasMany(EmailRelation::class);
    }
    
    public function deals() {
        return $this->morphToMany(Deal::class, 'related', 'email_relations');
    }
    
    public function companies() {
        return $this->morphToMany(Company::class, 'related', 'email_relations');
    }
    
    public function contacts() {
        return $this->morphToMany(Contact::class, 'related', 'email_relations');
    }
}
