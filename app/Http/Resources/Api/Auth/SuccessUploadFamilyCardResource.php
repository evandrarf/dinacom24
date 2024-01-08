<?php

namespace App\Http\Resources\Api\Auth;

use App\Actions\Utility\GetFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessUploadFamilyCardResource extends JsonResource
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
                'family_card_file' => $this->resource->family_card_file_id ? $getFile->handle($this->resource->family_card_file_id)->full_path : null,
            ],
            'meta' => [
                'success' => true,
                'message' => $this->message,
            ],
        ];
    }
}
