<?php

namespace App\Http\Resources\Api\Absence;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailReportResource extends JsonResource
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
        Carbon::setLocale('id');
        return [
            'data' => [
                'ticket_id' => $this->resource->ticket->ticket_number,
                'name' => $this->resource->social_assistance->name,
                'amount' => $this->resource->social_assistance->amount_per_kk,
                'taken_date' => $this->resource->taken_at ? Carbon::parse($this->resource->taken_at)->isoFormat('D MMMM Y') : null,
                'taken_time' => $this->resource->taken_at ? Carbon::parse($this->resource->taken_at)->isoFormat('HH:mm') : null,
                'recipient_head_of_family_name' => $this->resource->resident->head_of_family_name,
                'recipient_family_card_number' => $this->resource->resident->family_card_number,
                'village' => $this->resource->resident->village->name,
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
            ],
        ];
    }
}
