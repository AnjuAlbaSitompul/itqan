<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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

            // USER
            'name' => 'required|string|max:255',

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
            ],

            'password' => 'required|string|min:6',

            'role' => [
                'required',
                'exists:roles,id',
            ],

            // PROFILE
            'nip' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'tamatan' => 'required|string|max:4',
            'domisili' => 'required|string|max:255',
            'tipe_bpjs' => 'required|string|max:100',
            'golongan' => 'required|string|max:4',
            'alamat' => 'required|string',
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
