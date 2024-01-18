<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\ApiBaseRequest;

class UploadFamilyCardRequest extends ApiBaseRequest
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
            'file' => 'required|file|mimes:jpg,jpeg,png'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'File harus diisi',
            'file.file' => 'File harus berupa file',
            'file.mimes' => 'File harus berupa file gambar'
        ];
    }
}
