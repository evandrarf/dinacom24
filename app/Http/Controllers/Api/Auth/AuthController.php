<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\UploadFamilyCardRequest;
use App\Http\Resources\Api\Auth\SubmitAuthResource;
use App\Http\Resources\Api\Auth\SuccessGetProfileResource;
use App\Http\Resources\Api\Auth\SuccessLoginResource;
use App\Services\Api\Auth\AuthService;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use stdClass;

class AuthController extends Controller
{
    private $authService;
    public function __construct(AuthService $authService)
    {
        $this->middleware('JwtMiddleware', ['except' => ['login']]);
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $jwtHeader = $request->header('X-JWT-AUTH');
        try {
            if (!$jwtHeader) return $this->verifyUsername($request);

            $session = JWT::decode($jwtHeader, new Key(config('jwt.secret'), config('jwt.algo')));

            if (!isset($session->next_step) || !$session->next_step) {
                throw new Exception("Token tidak valid", 403);
            }

            if ($session->next_step === 'activation') return $this->activateAccount($request, $session);
            else if ($session->next_step === 'set_password') return $this->setPassword($request, $session);
            else if ($session->next_step === 'verify_password') return $this->verifyPassword($request, $session);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function verifyUsername(LoginRequest $request)
    {
        try {
            $data = $this->authService->verifyUsername($request);

            $payload = [
                'family_card_number' => $data->family_card_number,
                'next_step' => $data->status === 'inactive' ? 'activation' : ($data->password ? 'verify_password' : 'set_password'),
                'exp' => time() + (60 * 5)
            ];

            $token = JWT::encode($payload, config('jwt.secret'), config('jwt.algo'));

            $res = new SubmitAuthResource([
                'token' => $token,
            ], 'Berhasil verifikasi nomor kartu keluarga');

            return $this->respond($res);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function activateAccount(LoginRequest $request, $session)
    {
        try {
            $data = $this->authService->activateAccount($request, $session);

            $payload = [
                'family_card_number' => $data->family_card_number,
                'next_step' => 'set_password',
                'exp' => time() + (60 * 5)
            ];

            $token = JWT::encode($payload, config('jwt.secret'), config('jwt.algo'));

            $res = new SubmitAuthResource([
                'token' => $token,
            ], 'Berhasil melakukan aktivasi akun');

            return $this->respond($res);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function setPassword(LoginRequest $request, $session)
    {
        try {
            $data = $this->authService->setPassword($request, $session);

            $token = Auth::guard('api')->login($data);

            $res = new SuccessLoginResource($data, $token, 'Berhasil mengatur password');

            return $this->respond($res);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function verifyPassword(LoginRequest $request, $session)
    {
        try {
            $data = $this->authService->verifyPassword($request, $session);

            $token = Auth::guard('api')->login($data);

            $res = new SuccessLoginResource($data, $token, 'Berhasil login');

            return $this->respond($res);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            Auth::guard('api')->logout();

            return $this->messageSuccess('Berhasil logout', 200);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();

            $data = new stdClass();
            $data->token = JWTAuth::refresh($token);

            $res = new SubmitAuthResource($data, 'Berhasil refresh token');

            return $this->respond($res);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function me()
    {
        try {
            $data = Auth::guard('api')->user();

            $res = new SuccessGetProfileResource($data, 'Berhasil mengambil data user');

            return $this->respond($res);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function uploadFamilyCard(UploadFamilyCardRequest $request)
    {
        try {
            $data = $this->authService->uploadFamilyCard($request);

            $res = new SuccessGetProfileResource($data, 'Berhasil mengupload foto kartu keluarga');

            return $this->respond($res);
        } catch (Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
