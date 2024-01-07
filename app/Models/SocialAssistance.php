<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAssistance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'recipient_count',
        'total_amount'
    ];

    public function recipients()
    {
        return $this->hasMany(SocialAssistanceRecipient::class);
    }

    public function getRecipientCountAttribute()
    {
        return $this->recipients()->count();
    }

    public function getTotalAmountAttribute()
    {
        return $this->recipients()->count() * $this->amount_per_kk;
    }

    public function residents()
    {
        return $this->belongsToMany(Resident::class, 'social_assistance_recipients', 'social_assistance_id', 'resident_id');
    }
}
