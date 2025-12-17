<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EmailAccount extends Model
{
    protected $fillable = [
        'user_id', 'email', 'password_encrypted', 'provider',
        'imap_host', 'imap_port', 'imap_ssl',
        'smtp_host', 'smtp_port', 'smtp_ssl', 'smtp_username', 'smtp_password_encrypted',
        'is_active', 'last_sync_at'
    ];
    
    protected $casts = [
        'imap_ssl' => 'boolean',
        'smtp_ssl' => 'boolean',
        'is_active' => 'boolean',
        'last_sync_at' => 'datetime',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function emails() {
        return $this->hasMany(Email::class);
    }
    
    public function getPasswordAttribute() {
        return $this->password_encrypted ? Crypt::decryptString($this->password_encrypted) : null;
    }
    
    public function setPasswordAttribute($value) {
        $this->attributes['password_encrypted'] = $value ? Crypt::encryptString($value) : null;
    }
    
    public function getSmtpPasswordAttribute() {
        return $this->smtp_password_encrypted ? Crypt::decryptString($this->smtp_password_encrypted) : null;
    }
    
    public function setSmtpPasswordAttribute($value) {
        $this->attributes['smtp_password_encrypted'] = $value ? Crypt::encryptString($value) : null;
    }
}
