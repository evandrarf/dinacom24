<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'reports';

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function social_assistance()
    {
        return $this->belongsTo(SocialAssistance::class);
    }
}
