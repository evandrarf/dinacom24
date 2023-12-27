<?php

namespace App\Http\Controllers\Admin\Village;

use App\Http\Controllers\Controller;
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
}
