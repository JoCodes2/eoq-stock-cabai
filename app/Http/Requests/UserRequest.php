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
            'name' => 'required|max:50',
            'name_market' => 'required|max:100',
            'email' => $this->is('v1/user/update/*')
                ? 'required|email|unique:users,email,' . $this->id
                : 'required|email|unique:users,email',
            'password' => $this->is('v1/user/update/*') ? 'nullable|min:8' : 'required|min:8',
            'password_confirmation' => $this->is('v1/user/update/*') ? 'nullable|same:password' : 'required|same:password',
            'address' => 'required',
            'phone_number' => 'required|numeric',
        ];

        // Hanya admin yang bisa mengedit role
        if ($user && $user->role === 'admin') {
            $rules['role'] = 'required|in:admin,supplier,market';
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 50 karakter.',
            'name_market.required' => 'Nama usaha wajib diisi.',
            'name_market.max' => 'Nama usaha tidak boleh lebih dari 100 karakter.',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email tidak boleh sama',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role harus dipilih dari opsi yang tersedia.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus memiliki setidaknya 8 karakter.',
            'password_confirmation.required' => 'Konfirmasi Password wajib diisi.',
            'password_confirmation.same' => 'Password tidak sama.',
            'address.required' => 'alamat wajib diisi',
            'phone_number.required' => 'nomor wajib diisi.',
            'phone_number.numeric' => 'nomor harus angka',
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
