<?php

namespace App\Http\Resources\Admin\Resident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request)
    {
        return [
            'data' => $this->getData(),
            'meta' => [
                "success" => true,
                "message" => "Sukses mendapatkan data penduduk",
            ]
        ];
    }

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'family_card_number' => $this->family_card_number,
            'head_of_family_nik' => $this->head_of_family_nik,
            'head_of_family_name' => $this->head_of_family_name,
            'eligibility_status' => $this->calculateEligibilityStatus() ? 'Layak' : 'Tidak Layak',
            'monthly_income' => $this->monthly_income,
            'dependent_count' => $this->dependent_count,
            'house_ownership' => $this->house_ownership,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'district' => $this->district,
            'city' => $this->city,
            'village_id' => $this->village_id,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'phone_number' => $this->phone_number,
            'house_type' => $this->house_type,
            'status' => $this->status,
            'family_card_file' => $this->familyCard ? $this->familyCard->file_path : null,
            'identity_card_file' => $this->identityCard ? $this->identityCard->file_path : null,
            'score' => $this->calculateEligibilityScore(),
            'address' => $this->address,
            'full_address' => $this->getFullAddressAttribute(),
        ];
    }
}
