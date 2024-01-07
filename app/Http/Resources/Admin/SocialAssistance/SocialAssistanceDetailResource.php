<?php

namespace App\Http\Resources\Admin\SocialAssistance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAssistanceDetailResource extends JsonResource
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
                "message" => "Sukses mendapatkan data bantuan sosial.",
            ]
        ];
    }

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description ?? null,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'end_date' => $this->end_date,
            'end_time' => $this->end_time,
            'amount_per_kk' => $this->amount_per_kk,
            'status' => $this->status,
            'resident_ids' => $this->recipients->pluck('resident_id')->toArray() ?? [],
            'start_datetime' => $this->start_date . ' ' . $this->start_time,
            'end_datetime' => $this->end_date . ' ' . $this->end_time,
            'total_amount' => $this->total_amount,
            'recipient_count' => $this->recipient_count,
        ];
    }
}
