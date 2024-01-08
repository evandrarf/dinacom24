<?php

namespace App\Services\Api\Auth;

use App\Models\Resident;
use App\Services\FileService;
use Exception;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    public function verifyUsername($request)
    {
        $username = $request->family_card_number;

        $resident = Resident::where('family_card_number', $username)->first();

        if (!$resident) throw new Exception('No Kartu Keluarga tidak ditemukan', 404);

        return $resident;
    }

    public function activateAccount($request, $session)
    {
        $resident = Resident::where('family_card_number', $session->family_card_number)->first();

        if (!$resident) {
            throw new Exception("No KK tidak ditemukan");
        }

        if ($resident->status === 'active') {
            throw new Exception("Akun sudah teraktivasi", 409);
        }

        $file = $request->file('file');

        $fileService = new FileService();

        $storedFile = $fileService->uploadFile($file, 'residents_ktp');

        $pathName = Storage::path(str_replace('storage', 'public', $storedFile->path_name));

        $fileName = $storedFile->file_name . '.' . $storedFile->extension;

        $response = Http::attach('file', file_get_contents($pathName), $fileName)->post('https://ktp-ocr-getnik.evandrarf.my.id/get-nik')->object();

        if (strtolower($response->status) !== 'success' || !$response->nik) {
            throw new Exception("Gagal memindai KTP, pastikan foto KTP terlihat dengan jelas", 500);
        }

        if ($response->nik !== $resident->head_of_family_nik) {
            throw new Exception("Verifikasi gagal, NIK tidak sesuai", 403);
        }

        $resident->update([
            'identity_card_file_id' => $storedFile->id,
            'status' => 'active'
        ]);

        return $resident;
    }

    public function setPassword($request, $session)
    {
        $resident = Resident::where('family_card_number', $session->family_card_number)->first();

        if (!$resident) {
            throw new Exception("No KK tidak ditemukan", 404);
        }

        if ($resident->status === 'inactive') {
            throw new Exception("Akun belum aktif, aktivasi diperlukan", 403);
        }

        if ($resident->password) {
            throw new Exception("Password sudah pernah di set", 409);
        }

        $resident->update([
            'password' => bcrypt($request->password)
        ]);

        return $resident;
    }

    public function verifyPassword($request, $session)
    {
        $resident = Resident::where('family_card_number', $session->family_card_number)->first();

        if (!$resident) {
            throw new Exception("No KK tidak ditemukan", 404);
        }

        if ($resident->status === 'inactive') {
            throw new Exception("Akun belum aktif, aktivasi diperlukan", 403);
        }

        if (!$resident->password) {
            throw new Exception("Akun belum melakukan set password", 403);
        }

        if (!password_verify($request->password, $resident->password)) {
            throw new Exception("Password salah");
        }

        return $resident;
    }

    public function uploadFamilyCard($request)
    {
        $file = $request->file('file');

        $fileService = new FileService();

        $storedFile = $fileService->uploadFile($file, 'residents_family_card');

        $user_id = auth('api')->user()->id;

        $resident = Resident::where('id', $user_id)->first();

        $resident->update([
            'family_card_file_id' => $storedFile->id
        ]);

        return $resident;
    }

    public function changePassword($request)
    {
        $user_id = auth('api')->user()->id;

        $resident = Resident::where('id', $user_id)->first();

        if (!password_verify($request->old_password, $resident->password)) {
            throw new Exception("Password lama salah", 403);
        }

        $resident->update([
            'password' => bcrypt($request->new_password)
        ]);

        return $resident;
    }
}
