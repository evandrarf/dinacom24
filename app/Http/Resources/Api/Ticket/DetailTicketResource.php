<?php

namespace App\Http\Resources\Api\Ticket;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailTicketResource extends JsonResource
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
                'ticket_id' => $this->resource->ticket_number,
                'name' => $this->resource->social_assistance->name,
                'amount' => $this->resource->social_assistance->amount_per_kk,
                'start_date' => Carbon::parse($this->resource->social_assistance->start_date)->isoFormat('D MMMM Y'),
                'end_date' => Carbon::parse($this->resource->social_assistance->end_date)->isoFormat('D MMMM Y'),
                'start_time' => $this->resource->social_assistance->start_time,
                'end_time' => $this->resource->social_assistance->end_time,
                'recipient_head_of_family_name' => $this->resource->resident->head_of_family_name,
                'recipient_family_card_number' => $this->resource->resident->family_card_number,
                'qr_code_url' => $this->resource->qr_code_file->full_path,
                'village' => $this->resource->resident->village->name,
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
            ],
        ];
    }
}
