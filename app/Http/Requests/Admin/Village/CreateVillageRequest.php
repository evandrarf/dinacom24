<?php

namespace App\Http\Requests\Admin\Village;

use Illuminate\Foundation\Http\FormRequest;

class CreateVillageRequest extends FormRequest
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
            'name' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama desa/kelurahan tidak boleh kosong',
            'name.string' => 'Nama desa/kelurahan harus berupa string',
        ];
    }
}
