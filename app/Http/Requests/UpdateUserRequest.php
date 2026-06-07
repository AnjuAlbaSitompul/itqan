<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');


        return [

            // USER
            'name' => 'required|string|max:255',

            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->ignore($userId),
            ],

            'password' => 'nullable|string|min:8',

            'role' => [
                'required',
                'exists:roles,id',
            ],


            // PROFILE
            'nip' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_masuk' => 'nullable|date',
            'tamatan' => 'nullable|string|max:4',
            'domisili' => 'nullable|string|max:255',
            'tipe_bpjs' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:4',
            'alamat' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'role.required' => 'The role field is required.',
            'role.exists' => 'The selected role is invalid.',
        ];
    }
}