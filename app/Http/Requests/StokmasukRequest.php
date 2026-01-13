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
        return [
            // 'kode_stok_masuk' biasanya di-generate di controller,
            // namun jika dikirim dari input, tetap gunakan aturan ini.
            'kode_stok_masuk'   => 'nullable|string|max:100',
            'master_data_id'    => 'required|uuid|exists:master_data,id',

            // Gunakan numeric karena di migrasi menggunakan decimal(10,2)
            // Batas max 99999999.99 (sesuai decimal 10,2)
            'jumlah'            => 'required|numeric|min:1|max:99999999',

            // Batas max sesuai decimal(12,2) di database
            'harga_beli_satuan' => 'required|numeric|min:0|max:9999999999',

            'nama_supplier'     => 'required|string|max:150',
            'no_invoice'        => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'master_data_id.required'    => 'Produk wajib dipilih.',
            'master_data_id.exists'      => 'Produk tidak ditemukan.',

            'jumlah.required'            => 'Jumlah stok wajib diisi.',
            'jumlah.numeric'             => 'Jumlah stok harus berupa angka (boleh desimal).',
            'jumlah.min'                 => 'Jumlah stok minimal 1.',
            'jumlah.max'                 => 'Jumlah stok terlalu besar, maksimal 99.999.999.',

            'harga_beli_satuan.required' => 'Harga beli satuan wajib diisi.',
            'harga_beli_satuan.numeric'  => 'Harga beli satuan harus berupa angka.',
            'harga_beli_satuan.min'      => 'Harga beli satuan tidak boleh kurang dari 0.',
            'harga_beli_satuan.max'      => 'Harga beli terlalu besar.',

            'nama_supplier.required'     => 'Nama supplier wajib diisi.',
            'nama_supplier.max'          => 'Nama supplier maksimal 150 karakter.',
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
