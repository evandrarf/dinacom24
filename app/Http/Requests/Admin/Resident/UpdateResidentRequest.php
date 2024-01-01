<?php

namespace App\Http\Requests\Admin\Resident;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResidentRequest extends FormRequest
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
            'family_card_number' => 'required|string|numeric|unique:residents,family_card_number,' . $this->id . '|digits:16',
            'head_of_family_nik' => 'required|string|numeric|unique:residents,head_of_family_nik,' . $this->id . '|digits:16',
            'head_of_family_name' => 'required|string',
            'address' => 'required|string',
            'rt' => 'required|string',
            'rw' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'village_id' => 'required|exists:villages,id',
            'province' => 'required|string',
            'postal_code' => 'required|string|numeric|digits:5',
            'monthly_income' => 'required|integer',
            'dependent_count' => 'required|integer',
            'house_ownership' => 'required|string',
            'house_type' => 'required|string',
            'phone_number' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'family_card_number.required' => 'Nomor Kartu Keluarga tidak boleh kosong',
            'family_card_number.string' => 'Nomor Kartu Keluarga harus berupa string',
            'family_card_number.numeric' => 'Nomor Kartu Keluarga harus berupa angka',
            'family_card_number.unique' => 'Nomor Kartu Keluarga sudah terdaftar',
            'family_card_number.digits' => 'Nomor Kartu Keluarga harus 16 digit',
            'head_of_family_nik.required' => 'NIK Kepala Keluarga tidak boleh kosong',
            'head_of_family_nik.string' => 'NIK Kepala Keluarga harus berupa string',
            'head_of_family_nik.numeric' => 'NIK Kepala Keluarga harus berupa angka',
            'head_of_family_nik.unique' => 'NIK Kepala Keluarga sudah terdaftar',
            'head_of_family_nik.digits' => 'NIK Kepala Keluarga harus 16 digit',
            'head_of_family_name.required' => 'Nama Kepala Keluarga tidak boleh kosong',
            'head_of_family_name.string' => 'Nama Kepala Keluarga harus berupa string',
            'address.required' => 'Alamat tidak boleh kosong',
            'address.string' => 'Alamat harus berupa string',
            'rt.required' => 'RT tidak boleh kosong',
            'rt.string' => 'RT harus berupa string',
            'rw.required' => 'RW tidak boleh kosong',
            'rw.string' => 'RW harus berupa string',
            'district.required' => 'Kecamatan tidak boleh kosong',
            'district.string' => 'Kecamatan harus berupa string',
            'city.required' => 'Kota tidak boleh kosong',
            'city.string' => 'Kota harus berupa string',
            'village_id.required' => 'Desa/Kelurahan tidak boleh kosong',
            'village_id.exists' => 'Desa/Kelurahan tidak ditemukan',
            'province.required' => 'Provinsi tidak boleh kosong',
            'province.string' => 'Provinsi harus berupa string',
            'postal_code.required' => 'Kode Pos tidak boleh kosong',
            'postal_code.string' => 'Kode Pos harus berupa string',
            'postal_code.numeric' => 'Kode Pos harus berupa angka',
            'postal_code.digits' => 'Kode Pos harus 5 digit',
            'monthly_income.required' => 'Penghasilan per bulan tidak boleh kosong',
            'monthly_income.integer' => 'Penghasilan per bulan harus berupa angka',
            'dependent_count.required' => 'Jumlah tanggungan tidak boleh kosong',
            'dependent_count.integer' => 'Jumlah tanggungan harus berupa angka',
            'house_ownership.required' => 'Status kepemilikan rumah tidak boleh kosong',
            'house_ownership.string' => 'Status kepemilikan rumah harus berupa string',
            'house_type.required' => 'Tipe rumah tidak boleh kosong',
            'house_type.string' => 'Tipe rumah harus berupa string',
            'phone_number.string' => 'Nomor telepon harus berupa string',
        ];
    }
}
