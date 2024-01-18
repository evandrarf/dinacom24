<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAssistanceRecipient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'social_assistance_recipients';

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function socialAssistance()
    {
        return $this->belongsTo(SocialAssistance::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
