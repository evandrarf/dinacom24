<?php

namespace App\Http\Resources\Api\Auth;

use App\Actions\Utility\GetFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessGetProfileResource extends JsonResource
{
    private $message;

    public function __construct($resource, $message)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->resource = $resource;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $getFile = new GetFile();
        return [
            'data' => [
                'id' => $this->resource->id,
                'family_card_number' => $this->resource->family_card_number,
                'head_of_family_nik' => $this->resource->head_of_family_nik,
                'head_of_family_name' => $this->resource->head_of_family_name,
                'eligibility_status' => $this->resource->calculateEligibilityStatus() ? 'Layak' : 'Tidak Layak',
                'monthly_income' => $this->resource->monthly_income,
                'dependent_count' => $this->resource->dependent_count,
                'house_ownership' => $this->resource->house_ownership,
                'rt' => $this->resource->rt,
                'rw' => $this->resource->rw,
                'district' => $this->resource->district,
                'city' => $this->resource->city,
                'village_id' => $this->resource->village_id,
                'village' => $this->resource->village->only('name'),
                'province' => $this->resource->province,
                'postal_code' => $this->resource->postal_code,
                'phone_number' => $this->resource->phone_number,
                'house_type' => $this->resource->house_type,
                'status' => $this->resource->status,
                'family_card_file' => $this->resource->family_card_file_id ? $getFile->handle($this->resource->family_card_file_id)->full_path : null,
                'identity_card_file' => $this->resource->identity_card_file_id ? $getFile->handle($this->resource->identity_card_file_id)->full_path : null,
                'score' => $this->resource->calculateEligibilityScore(),
                'address' => $this->resource->address,
                'full_address' => $this->resource->full_address
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
            ],
        ];
    }
}
