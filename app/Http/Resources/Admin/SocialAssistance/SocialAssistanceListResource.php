<?php

namespace App\Http\Resources\Admin\SocialAssistance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SocialAssistanceListResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Sukses mendapatkan data bantuan sosial",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'status' => $data->status,
            'amount_per_kk' => $data->amount_per_kk,
            'start_date' => $data->start_date,
            'end_date' => $data->end_date,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'total_amount' => $data->total_amount,
            'recipient_count' => $data->recipient_count,
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
