<?php

namespace App\Http\Resources\Api\Auth;

use App\Actions\Utility\GetFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessSummaryResource extends JsonResource
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
                'user' => [
                    'id' => $this->resource->id,
                    'family_card_number' => $this->resource->family_card_number,
                    'head_of_family_nik' => $this->resource->head_of_family_nik,
                    'head_of_family_name' => $this->resource->head_of_family_name,
                    'eligibility_status' => $this->resource->calculateEligibilityStatus() ? 'Layak' : 'Tidak Layak',
                    'score' => $this->resource->calculateEligibilityScore(),
                    'family_card_file' => $this->resource->family_card_file_id ? $getFile->handle($this->resource->family_card_file_id)->full_path : null,
                    'identity_card_file' => $this->resource->identity_card_file_id ? $getFile->handle($this->resource->identity_card_file_id)->full_path : null,
                    'full_address' => $this->resource->full_address
                ]
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
            ],
        ];
    }
}
