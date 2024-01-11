<?php

namespace App\Http\Controllers\Api\SocialAssistance;

use App\Http\Controllers\ApiBaseController;
use App\Http\Resources\Api\SocialAssistance\SocialAssistanceListResource;
use App\Services\Api\SocialAssistance\SocialAssistanceService;
use Illuminate\Http\Request;

class SocialAssistanceController extends ApiBaseController
{
    private $socialAssistanceService;

    public function __construct(SocialAssistanceService $socialAssistanceService)
    {
        $this->socialAssistanceService = $socialAssistanceService;
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->socialAssistanceService->getData($request);

            $res = new SocialAssistanceListResource($data, 'Sukses mendapatkan data bantuan sosial.');

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), $e->getCode());
        }
    }
}
