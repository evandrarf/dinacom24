<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAssistanceRecipient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'social_assistance_recipients';
}
