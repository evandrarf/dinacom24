<?php

namespace App\Http\Resources\Api\SocialAssistance;

use Carbon\Carbon;
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
            ]
        ];
    }

    private function transformData($data, $key)
    {
        Carbon::setLocale('id');
        return [
            'month' => Carbon::parse($key)->isoFormat('MMMM Y'),
            'social_assistances' => $data
        ];
    }

    private function transformCollection($collection)
    {
        return $collection->transform(function ($data, $key) {
            return $this->transformData($data, $key);
        })->values();
    }
}
