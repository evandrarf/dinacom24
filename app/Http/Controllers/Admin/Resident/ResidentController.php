<?php

namespace App\Http\Controllers\Admin\Resident;

use App\Actions\Options\GetDistrictOptions;
use App\Actions\Options\GetVillageOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Resident\CreateResidentRequest;
use App\Http\Requests\Admin\Resident\DestroyManyResidentRequest as ResidentDestroyManyResidentRequest;
use App\Http\Requests\Admin\Resident\UpdateResidentRequest;
use App\Http\Resources\Admin\BaseSubmitResource;
use App\Http\Resources\Admin\Resident\ResidentDetailResource;
use App\Http\Resources\Admin\Resident\ResidentListResource;
use App\Services\Admin\Resident\ResidentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResidentController extends Controller
{
    private $residentService, $getDistrictOptions, $getVillageOptions;

    public function __construct(ResidentService $residentService, GetDistrictOptions $getDistrictOptions, GetVillageOptions $getVillageOptions)
    {
        $this->residentService = $residentService;
        $this->getDistrictOptions = $getDistrictOptions;
        $this->getVillageOptions = $getVillageOptions;
    }

    public function index()
    {
        return Inertia::render('Admin/Resident/Index', [
            'additional' => [
                'districts' => $this->getDistrictOptions->handle(),
                'villages' => $this->getVillageOptions->handle()
            ]
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->residentService->getData($request);

            $res = new ResidentListResource($data);

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        return Inertia::render('Admin/Resident/Show', [
            'id' => $id
        ]);
    }

    public function getDetailData(Request $request, $id)
    {
        try {
            $data = $this->residentService->getDetailData($request, $id);

            $res = new ResidentDetailResource($data);

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function create()
    {
        return Inertia::render('Admin/Resident/Create', [
            'additional' => [
                'districts' => $this->getDistrictOptions->handle(),
                'villages' => $this->getVillageOptions->handle()
            ]
        ]);
    }

    public function store(CreateResidentRequest $request)
    {
        try {
            $data = $this->residentService->store($request);

            $res = new BaseSubmitResource($data, 'Berhasil menambahkan data warga');

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function edit($id)
    {
        return Inertia::render('Admin/Resident/Edit', [
            'id' => $id,
            'additional' => [
                'districts' => $this->getDistrictOptions->handle(),
                'villages' => $this->getVillageOptions->handle()
            ]
        ]);
    }

    public function update(UpdateResidentRequest $request, $id)
    {
        try {
            $data = $this->residentService->update($request, $id);

            $res = new BaseSubmitResource($data, 'Berhasil mengubah data warga');

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = $this->residentService->destroy($id);

            $res = new BaseSubmitResource($data, 'Berhasil menghapus data warga');

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroyMany(ResidentDestroyManyResidentRequest $request)
    {
        try {
            $data = $this->residentService->destroyMany($request);

            $res = new BaseSubmitResource($data, 'Berhasil menghapus data warga');

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }
}
