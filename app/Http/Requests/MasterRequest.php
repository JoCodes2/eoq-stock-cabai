<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class MasterRequest extends FormRequest
{

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
        $rules = [

            'nama'          => 'required|string|max:255',
            'satuan'        => 'required|string|max:20',


        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
          

            'nama.required'         => 'Nama wajib diisi.',
            'nama.max'              => 'Nama maksimal 255 karakter.',

            'satuan.required'       => 'Satuan wajib dipilih.',
            'satuan.max'            => 'Satuan maksimal 20 karakter.',



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
