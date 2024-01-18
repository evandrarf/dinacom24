<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Absence\DetailReportResource;
use App\Services\Api\Report\ReportService;
use Illuminate\Http\Request;

class ReportController extends ApiBaseController
{
    private $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function show($id)
    {
        try {
            $data = $this->reportService->show($id);

            $res = new DetailReportResource($data, "Sukses menampilkan detail laporan");

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), $e->getCode());
        }
    }
}
