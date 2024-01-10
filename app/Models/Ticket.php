<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'tickets';

    public function socialAssistanceRecipient()
    {
        return $this->belongsTo(SocialAssistanceRecipient::class);
    }

    public function qrCodeFile()
    {
        return $this->belongsTo(File::class, 'qr_code_file_id');
    }

    public function socialAssistance()
    {
        return $this->belongsTo(SocialAssistance::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
