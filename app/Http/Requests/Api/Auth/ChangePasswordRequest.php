<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\ApiBaseRequest;

class ChangePasswordRequest extends ApiBaseRequest
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
        return [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */

    public function messages(): array
    {
        return [
            'old_password.required' => 'Password lama harus diisi',
            'old_password.string' => 'Password lama harus berupa string',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.string' => 'Password baru harus berupa string',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Password baru tidak sama dengan konfirmasi password'
        ];
    }
}
