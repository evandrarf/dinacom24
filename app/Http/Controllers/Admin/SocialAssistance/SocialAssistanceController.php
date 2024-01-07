<?php

namespace App\Http\Controllers\Admin\SocialAssistance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialAssistance\CreateSocialAssistanceRequest;
use App\Http\Requests\Admin\SocialAssistance\DestroyManySocialAssistanceRequest;
use App\Http\Resources\Admin\BaseSubmitResource;
use App\Http\Resources\Admin\SocialAssistance\SocialAssistanceDetailResource;
use App\Http\Resources\Admin\SocialAssistance\SocialAssistanceListResource;
use App\Http\Resources\Admin\SocialAssistance\SocialAssistanceResidentResource;
use App\Services\Admin\SocialAssistance\SocialAssistanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SocialAssistanceController extends Controller
{
    private $socialAssistanceService;

    public function __construct(SocialAssistanceService $socialAssistanceService)
    {
        $this->socialAssistanceService = $socialAssistanceService;
    }

    public function index()
    {
        return Inertia::render('Admin/SocialAssistance/Index');
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->socialAssistanceService->getData($request);

            $res = new SocialAssistanceListResource($data);

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function create()
    {
        return Inertia::render('Admin/SocialAssistance/Create');
    }

    public function store(CreateSocialAssistanceRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->socialAssistanceService->store($request);

            $res = new BaseSubmitResource($data, 'Bantuan Sosial berhasil ditambahkan.');

            DB::commit();
            return $this->respond($res);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        return Inertia::render('Admin/SocialAssistance/Show', [
            'id' => $id
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Admin/SocialAssistance/Edit', [
            'id' => $id
        ]);
    }

    public function getDetailData($id)
    {
        try {
            $data = $this->socialAssistanceService->getDetailData($id);

            $res = new SocialAssistanceDetailResource($data);

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function getResidentData($id)
    {
        try {
            $data = $this->socialAssistanceService->getResidentData($id);

            $res = new SocialAssistanceResidentResource($data);

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function update(CreateSocialAssistanceRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->socialAssistanceService->update($request, $id);

            $res = new BaseSubmitResource($data, 'Bantuan Sosial berhasil diubah.');

            DB::commit();
            return $this->respond($res);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = $this->socialAssistanceService->destroy($id);

            $res = new BaseSubmitResource($data, 'Bantuan Sosial berhasil dihapus.');

            DB::commit();
            return $this->respond($res);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroyMany(DestroyManySocialAssistanceRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->socialAssistanceService->destroyMany($request);

            $res = new BaseSubmitResource($data, 'Bantuan Sosial berhasil dihapus.');

            DB::commit();
            return $this->respond($res);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exceptionError($e->getMessage(), 500);
        }
    }
}
