<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Resident extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'full_address'
    ];

    protected $hidden = [
        'password',
    ];

    public function familyCard()
    {
        return $this->hasOne(File::class, 'id', 'family_card_file_id');
    }

    public function identityCard()
    {
        return $this->hasOne(File::class, 'id', 'identity_card_file_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, RT {$this->rt}, RW {$this->rw}, {$this->village->name}, {$this->district}, {$this->city}, {$this->province}, {$this->postal_code}";
    }

    public function getFullAddressWithoutProvinceAttribute()
    {
        return "{$this->address}, RT {$this->rt}, RW {$this->rw}, {$this->village->name}, {$this->district}, {$this->city}, {$this->postal_code}";
    }

    public function calculateEligibilityScore()
    {
        $weight = [
            'ratio' => 0.8,
            'house_ownership' => 0.1,
            'house_type' => 0.1,
        ];

        $score = 0;

        $dependent_count = $this->dependent_count - 1;

        $ratio = $dependent_count > 0 ? $this->monthly_income / $dependent_count : $this->monthly_income;

        switch ($ratio) {
            case $ratio <= 500000:
                $score += $weight['ratio'] * 10;
                break;
            case  $ratio <= 1000000:
                $score += $weight['ratio'] * 8;
                break;
            case $ratio <= 1500000:
                $score += $weight['ratio'] * 6;
                break;
            case $ratio <= 2000000:
                $score += $weight['ratio'] * 5;
                break;
            case $ratio <= 4000000:
                $score += $weight['ratio'] * 4;
                break;
            case $ratio <= 10000000:
                $score += $weight['ratio'] * 1;
                break;
            default:
                $score += $weight['ratio'] * 0;
                break;
        }

        switch ($this->house_ownership) {
            case 'owned' && $ratio <= 1000000:
                $score += $weight['house_ownership'] * 7;
                break;
            case 'owned' && $ratio <= 2000000:
                $score += $weight['house_ownership'] * 5;
                break;
            case 'owned' && $ratio <= 4000000:
                $score += $weight['house_ownership'] * 1;
                break;
            case 'join':
                $score += $weight['house_ownership'] * 5;
                break;
            case 'rented':
                $score += $weight['house_ownership'] * 7;
                break;
            default:
                $score += $weight['house_ownership'] * 1;
                break;
        }

        switch ($this->house_type) {
            case 'permanent' && $ratio <= 1000000:
                $score += $weight['house_ownership'] * 7;
                break;
            case 'permanent' && $ratio <= 2000000:
                $score += $weight['house_ownership'] * 5;
                break;
            case 'permanent' && $ratio <= 4000000:
                $score += $weight['house_ownership'] * 2;
                break;
            case 'semi_permanent':
                $score += $weight['house_type'] * 8;
                break;
            default:
                $score += $weight['house_type'] * 1;
                break;
        }

        return round($score, 2);
    }

    public function calculateEligibilityStatus()
    {
        $score = $this->calculateEligibilityScore();

        if ($score >= 6) {
            return true;
        }

        return false;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function social_assistance()
    {
        return $this->belongsToMany(SocialAssistance::class, 'social_assistance_residents', 'resident_id', 'social_assistance_id');
    }
}
