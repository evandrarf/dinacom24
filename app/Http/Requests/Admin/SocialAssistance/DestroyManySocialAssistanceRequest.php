<?php

namespace App\Http\Requests\Admin\SocialAssistance;

use Illuminate\Foundation\Http\FormRequest;

class DestroyManySocialAssistanceRequest extends FormRequest
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
            'ids.*' => 'required|exists:users,id'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'ids.required' => 'ID tidak boleh kosong',
            'ids.array' => 'ID harus berupa array',
            'ids.*.required' => 'ID tidak boleh kosong',
            'ids.*.exists' => 'ID tidak ditemukan',
        ];
    }
}
