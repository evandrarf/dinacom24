<?php

namespace App\Http\Requests\Admin\Village;

use Illuminate\Foundation\Http\FormRequest;

class DestroyManyVillageRequest extends FormRequest
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
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:villages,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */

    public function messages(): array
    {
        return [
            'ids.required' => 'ID desa tidak boleh kosong',
            'ids.array' => 'ID desa harus berupa array',
            'ids.*.required' => 'ID desa tidak boleh kosong',
            'ids.*.integer' => 'ID desa harus berupa angka',
            'ids.*.exists' => 'ID desa tidak ditemukan',
        ];
    }
}
