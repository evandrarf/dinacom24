<?php

namespace App\Http\Requests\Admin\Absence;

use Illuminate\Foundation\Http\FormRequest;

class GetDetailRecipientRequest extends FormRequest
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
            'ticket_number' => 'required|numeric|digits:17'
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
            'ticket_number.required' => 'Nomor tiket tidak boleh kosong',
            'ticket_number.numeric' => 'Nomor tiket harus berupa angka',
            'ticket_number.digits' => 'Nomor tiket harus memiliki panjang 17 digit'
        ];
    }
}
