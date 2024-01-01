<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\ApiBaseRequest;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $jwtHeader = request()->header('X-JWT-AUTH');

        if (!$jwtHeader) return ['family_card_number' => 'required|numeric|digits:16'];

        $session = JWT::decode($jwtHeader, new Key(config('jwt.secret'), config('jwt.algo')));

        if ($session->next_step === 'activation') return ['file' => 'required|file|mimes:png,jpg,jpeg,gif'];
        else if ($session->next_step === 'set_password') return ['password' => 'required|string|min:8|confirmed'];
        else if ($session->next_step === 'verify_password') return ['password' => 'required|string|min:8'];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'family_card_number.required' => 'Nomor kartu keluarga tidak boleh kosong',
            'family_card_number.numeric' => 'Nomor kartu keluarga harus berupa angka',
            'family_card_number.digits' => 'Nomor kartu keluarga harus berjumlah 16 digit',
            'file.required' => 'Foto tidak boleh kosong',
            'file.file' => 'Foto harus berupa file',
            'file.mimes' => 'Foto harus berupa file gambar',
            'password.required' => 'Password tidak boleh kosong',
            'password.string' => 'Password harus berupa string',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak cocok dengan konfirmasi password',
        ];
    }
}
