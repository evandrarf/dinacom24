<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
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

        $ratio = $this->monthly_income / $this->dependent_count;

        switch ($ratio) {
            case  $ratio <= 1000000:
                $score += $weight['ratio'] * 10;
                break;
            case $ratio <= 2000000:
                $score += $weight['ratio'] * 8;
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
                $score += $weight['house_ownership'] * 8;
                break;
            case 'rented':
                $score += $weight['house_ownership'] * 10;
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

        if ($score >= 5) {
            return true;
        }

        return false;
    }
}
