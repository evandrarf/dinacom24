<?php

namespace App\Http\Resources\Api\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReadNotificationResource extends JsonResource
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

        return [
            'data' => [
                'id' => $this->resource->id,
                'title' => $this->resource->title,
                'body' => $this->resource->body,
                'data_id' => $this->resource->data_id,
                'data_type' => $this->resource->data_type,
                'is_read' => $this->resource->is_read,
                'created_at' => $this->resource->created_at,
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
            ],
        ];
    }
}
