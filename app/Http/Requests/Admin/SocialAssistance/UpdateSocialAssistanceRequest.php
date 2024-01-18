<?php

namespace App\Http\Requests\Admin\SocialAssistance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialAssistanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:5',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
            'resident_ids' => 'required|array',
            'resident_ids.*' => 'required|integer|exists:residents,id',
            'amount_per_kk' => 'required|integer',
            'status' => 'required|in:draft,active,finished'
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
            'name.required' => 'Nama Bantuan Sosial harus diisi.',
            'name.string' => 'Nama Bantuan Sosial harus berupa string.',
            'name.min' => 'Nama Bantuan Sosial minimal 5 karakter.',
            'name.max' => 'Nama Bantuan Sosial maksimal 255 karakter.',
            'description.required' => 'Deskripsi Bantuan Sosial harus diisi.',
            'description.string' => 'Deskripsi Bantuan Sosial harus berupa string.',
            'description.min' => 'Deskripsi Bantuan Sosial minimal 5 karakter.',
            'start_datetime.required' => 'Tanggal Mulai Bantuan Sosial harus diisi.',
            'start_datetime.date' => 'Tanggal Mulai Bantuan Sosial harus berupa tanggal.',
            'end_datetime.required' => 'Tanggal Selesai Bantuan Sosial harus diisi.',
            'end_datetime.date' => 'Tanggal Selesai Bantuan Sosial harus berupa tanggal.',
            'resident_ids.required' => 'Penerima Bantuan Sosial harus diisi.',
            'resident_ids.array' => 'Penerima Bantuan Sosial harus berupa array.',
            'resident_ids.*.required' => 'Penerima Bantuan Sosial harus diisi.',
            'resident_ids.*.integer' => 'Penerima Bantuan Sosial harus berupa integer.',
            'resident_ids.*.exists' => 'Penerima Bantuan Sosial tidak ditemukan.',
            'amount_per_kk.required' => 'Jumlah Bantuan Sosial per KK harus diisi.',
            'amount_per_kk.integer' => 'Jumlah Bantuan Sosial per KK harus berupa integer.',
            'status.required' => 'Status Bantuan Sosial harus diisi.',
            'status.in' => 'Status Bantuan Sosial tidak valid.',
        ];
    }
}
