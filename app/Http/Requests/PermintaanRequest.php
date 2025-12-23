<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermintaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pengguna_id'      => 'required|exists:users,id',
            'catatan'          => 'nullable|string|max:500',

            'items'                     => 'required|array|min:1',
            'items.*.master_data_id'    => 'required|exists:master_data,id',
            'items.*.jumlah'            => 'required|numeric|min:0.01',
            'items.*.harga_satuan'      => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'                => 'Daftar pesanan cabai tidak boleh kosong.',
            'items.*.master_data_id.exists' => 'Data cabai tidak ditemukan dalam sistem.',
            'items.*.jumlah.required'       => 'Jumlah berat harus diisi.',
            'items.*.jumlah.numeric'        => 'Jumlah harus berupa angka.',
            'items.*.jumlah.min'            => 'Minimal pembelian adalah 0.01 kg.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => 'Check your validation',
            'errors' => $validator->errors()
        ]));
    }
}
