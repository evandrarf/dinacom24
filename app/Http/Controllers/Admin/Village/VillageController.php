<?php

namespace App\Http\Controllers\Admin\Village;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Village\CreateVillageRequest;
use App\Http\Requests\Admin\Village\DestroyManyVillageRequest;
use App\Http\Resources\Admin\BaseSubmitResource;
use App\Http\Resources\Admin\Village\VillageListResource;
use App\Services\Admin\Village\VillageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VillageController extends Controller
{
    private $villageService;

    public function __construct(VillageService $villageService)
    {
        $this->villageService = $villageService;
    }

    public function index()
    {
        return Inertia::render('Admin/Village/Index');
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->villageService->getData($request);

            $res = new VillageListResource($data);

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function store(CreateVillageRequest $request)
    {
        try {
            $data = $this->villageService->store($request);

            $res = new BaseSubmitResource($data, 'Sukses menambahkan data desa');

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = $this->villageService->destroy($request, $id);

            $res = new BaseSubmitResource($data, 'Sukses menghapus data desa');

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroyMany(DestroyManyVillageRequest $request)
    {
        try {
            $data = $this->villageService->destroyMany($request);

            $res = new BaseSubmitResource($data, 'Sukses menghapus data desa');

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }
}
