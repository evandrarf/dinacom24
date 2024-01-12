<?php

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\ApiBaseController;
use App\Http\Resources\Api\Notification\ListNotificationResource;
use App\Services\Api\Notification\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends ApiBaseController
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->notificationService->getData($request);

            $res = new ListNotificationResource($data, 'Sukses mendapatkan data notifikasi.');

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), $e->getCode());
        }
    }
}
