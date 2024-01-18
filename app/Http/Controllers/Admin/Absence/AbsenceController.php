<?php

namespace App\Http\Controllers\Admin\Absence;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Absence\GetDetailRecipientRequest;
use App\Http\Resources\Admin\Absence\DetailRecipientResource;
use App\Http\Resources\Admin\BaseSubmitResource;
use App\Services\Admin\Absence\AbsenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AbsenceController extends Controller
{
    private $absenceService;

    public function __construct(AbsenceService $absenceService)
    {
        $this->absenceService = $absenceService;
    }

    public function index()
    {
        return Inertia::render('Admin/Absence/Index');
    }

    public function getDetailRecipient(GetDetailRecipientRequest $request)
    {
        try {
            $data = $this->absenceService->getDetailRecipient($request);

            $res = new DetailRecipientResource($data);

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), $e->getCode());
        }
    }

    public function confirm(GetDetailRecipientRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->absenceService->confirm($request);

            $res = new BaseSubmitResource($data, 'Absensi penerima bantuan sosial berhasil');

            DB::commit();

            return $this->respond($res);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage(), $e->getCode());
        }
    }
}
