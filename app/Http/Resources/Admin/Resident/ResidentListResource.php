<?php

namespace App\Http\Resources\Admin\Resident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResidentListResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Sukses mendapatkan data penduduk",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        return [
            'id' => $data->id,
            'family_card_number' => $data->family_card_number,
            'head_of_family_nik' => $data->head_of_family_nik,
            'head_of_family_name' => $data->head_of_family_name,
            'eligibility_status' => $data->calculateEligibilityStatus() ? 'Layak' : 'Tidak Layak',
            'monthly_income' => $data->monthly_income,
            'dependent_count' => $data->dependent_count,
            'house_ownership' => $data->house_ownership,
            'rt' => $data->rt,
            'rw' => $data->rw,
            'district' => $data->district,
            'city' => $data->city,
            'village_id' => $data->village_id,
            'province' => $data->province,
            'postal_code' => $data->postal_code,
            'phone_number' => $data->phone_number,
            'house_type' => $data->house_type,
            'family_card_file' => $data->familyCard ? $data->familyCard->file_path : null,
            'identity_card_file' => $data->identityCard ? $data->identityCard->file_path : null,
            'score' => $data->calculateEligibilityScore(),
            'full_address' => $data->full_address
        ];
    }

    private function transformCollection($collection)
    {
        return $collection->transform(function ($data) {
            return $this->transformData($data);
        });
    }

    private function metaData()
    {
        return [
            "total" => $this->total(),
            "count" => $this->count(),
            "per_page" => (int)$this->perPage(),
            "current_page" => $this->currentPage(),
            "total_pages" => $this->lastPage(),
            "links" => [
                "next" => $this->nextPageUrl()
            ],
        ];
    }
}
