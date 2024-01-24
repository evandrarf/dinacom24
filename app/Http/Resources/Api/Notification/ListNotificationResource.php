<?php

namespace App\Http\Resources\Api\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListNotificationResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Sukses mendapatkan data notifikasi.",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        return [
            'id' => $data->id,
            'title' => $data->title,
            'body' => $data->body,
            'data_id' => $data->data_id,
            'data_type' => $data->data_type,
            'is_read' => $data->is_read,
            'created_time' => $data->created_time,
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
            "path" => $this->path(),
            "next_cursor" => $this->nextCursor() ? $this->nextCursor()->encode() : null,
            "per_page" => $this->perPage(),
            "links" => [
                'next' =>  $this->nextPageUrl() ? $this->nextPageUrl() . '&per_page=' . $this->perPage() : null,
            ],
        ];
    }
}
