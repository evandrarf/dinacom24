<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\Options\GetVillageOptions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateUserRequest;
use App\Http\Requests\Admin\User\DestroyManyUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\Admin\BaseSubmitResource;
use App\Http\Resources\Admin\User\UserListResource;
use App\Services\Admin\User\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserManagementController extends Controller
{
    private $userService, $getVillageOptions;

    public function __construct(UserService $userService, GetVillageOptions $getVillageOptions)
    {
        $this->userService = $userService;
        $this->getVillageOptions = $getVillageOptions;
    }

    public function index()
    {
        return Inertia::render('Admin/UserManagement/Index', [
            'additional' => [
                'villages' => $this->getVillageOptions->handle()
            ],
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $data = $this->userService->getData($request);

            $res = new UserListResource($data);

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $data = $this->userService->store($request);

            $res = new BaseSubmitResource($data, 'Sukses menambahkan data user');

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $data = $this->userService->update($request, $id);

            $res = new BaseSubmitResource($data, 'Sukses mengubah data user');

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = $this->userService->destroy($request, $id);

            $res = new BaseSubmitResource($data, 'Sukses menghapus data user');

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }

    public function destroyMany(DestroyManyUserRequest $request)
    {
        try {
            $data = $this->userService->destroyMany($request);

            $res = new BaseSubmitResource($data, 'Sukses menghapus data user');

            return $this->respond($res, 200);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), 500);
        }
    }
}
