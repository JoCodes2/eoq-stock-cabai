<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
        $user = Auth::user(); // ambil user yang sedang login

        $rules = [
            'nama' => 'required|max:50',
            'email' => $this->is('v1/user/update/*')
                ? 'required|email|unique:users,email,' . $this->id
                : 'required|email|unique:users,email',
            'password' => $this->is('v1/user/update/*') ? 'nullable|min:8' : 'required|min:8',
            'alamat' => 'required',
            'no_hp' => 'required|numeric',
        ];

        // Hanya admin yang bisa mengedit role
        if ($user && $user->role === 'admin') {
            $rules['role'] = 'required|in:admin,pembeli';
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama tidak boleh lebih dari 50 karakter.',

            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email tidak boleh sama',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role harus dipilih dari opsi yang tersedia.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus memiliki setidaknya 8 karakter.',

            'alamat.required' => 'alamat wajib diisi',
            'no_hp.required' => 'nomor wajib diisi.',
            'no_hp.numeric' => 'nomor harus angka',
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
