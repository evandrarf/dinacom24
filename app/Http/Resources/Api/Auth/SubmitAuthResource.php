<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmitAuthResource extends JsonResource
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
            'data' => $this->resource,
            'meta' => [
                'success' => true,
                'message' => $this->message,
            ],
        ];
    }
}
