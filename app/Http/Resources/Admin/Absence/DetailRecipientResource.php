<?php

namespace App\Http\Resources\Admin\Absence;

use App\Actions\Utility\GetFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailRecipientResource extends JsonResource
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
                "message" => "Sukses mendapatkan data penerima bantuan",
            ]
        ];
    }

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'resident' => [
                'id' => $this->resident->id,
                'family_card_number' => $this->resident->family_card_number,
                'head_of_family_nik' => $this->resident->head_of_family_nik,
                'head_of_family_name' => $this->resident->head_of_family_name,
                'family_card_file' => $this->resident->family_card_file_id ? (new GetFile())->handle($this->resident->family_card_file_id)->full_path : null,
                'identity_card_file' => $this->resident->identity_card_file_id ? (new GetFile())->handle($this->resident->identity_card_file_id)->full_path : null,
            ],
            'ticket_number' => $this->ticket_number,
            'social_assistance' => $this->social_assistance->only(['id', 'name', 'amount_per_kk']),
        ];
    }
}
