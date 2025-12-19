<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StokmasukRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'kode_stok_masuk'   => 'required|string|max:100',
            'master_data_id'    => 'required|uuid|exists:master_data,id',
            'jumlah'            => 'required|integer|min:1',
            'harga_beli_satuan' => 'required|numeric|min:0',
            'nama_supplier'     => 'required|string|max:150',
            'no_invoice'        => 'nullable|string|max:100',

        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'kode_stok_masuk.required'   => 'Kode stok masuk wajib diisi.',
            'kode_stok_masuk.max'        => 'Kode stok masuk maksimal 100 karakter.',

            'master_data_id.required'    => 'Produk wajib dipilih.',
            'master_data_id.uuid'        => 'Format ID produk tidak valid.',
            'master_data_id.exists'      => 'Produk tidak ditemukan.',

            'jumlah.required'            => 'Jumlah stok wajib diisi.',
            'jumlah.integer'             => 'Jumlah stok harus berupa angka.',
            'jumlah.min'                 => 'Jumlah stok minimal 1.',

            'harga_beli_satuan.required' => 'Harga beli satuan wajib diisi.',
            'harga_beli_satuan.numeric'  => 'Harga beli satuan harus berupa angka.',
            'harga_beli_satuan.min'      => 'Harga beli satuan tidak boleh kurang dari 0.',

            'nama_supplier.required'     => 'Nama supplier wajib diisi.',
            'nama_supplier.max'          => 'Nama supplier maksimal 150 karakter.',

            'no_invoice.max'              => 'Nomor invoice maksimal 100 karakter.',
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
