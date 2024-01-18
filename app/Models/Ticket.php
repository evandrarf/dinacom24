<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
  use HasFactory;

  protected $guarded = ['id'];

  protected $table = 'tickets';

  public function social_assistance_recipient()
  {
    return $this->belongsTo(SocialAssistanceRecipient::class);
  }

  public function qr_code_file()
  {
    return $this->belongsTo(File::class, 'qr_code_file_id');
  }

  public function social_assistance()
  {
    return $this->belongsTo(SocialAssistance::class);
  }

  public function resident()
  {
    return $this->belongsTo(Resident::class);
  }

  public function report()
  {
    return $this->hasOne(Report::class);
  }
}
