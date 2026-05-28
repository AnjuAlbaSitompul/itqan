<?php

namespace App\Http\Requests;

use App\Models\roles;
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
        $requiredOutletRoles = roles::query()
            ->whereIn('name', [
                'spv',
                'pegawai',
                'manager',
            ])
            ->pluck('id')
            ->toArray();

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

            'outlet_id' => [
                Rule::requiredIf(
                    in_array($this->role, $requiredOutletRoles)
                ),
                'nullable',
                'exists:outlets,id',
            ],

            // PROFILE
            'nip' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'tamatan' => 'required|string|max:255',
            'domisili' => 'required|string|max:255',
            'tipe_bpjs' => 'required|string|max:100',
            'golongan' => 'required|string|max:100',
            'unit_id' => 'required|exists:units,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'alamat' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'outlet_id.required' => 'Outlet wajib dipilih untuk role ini.',
        ];
    }
}
