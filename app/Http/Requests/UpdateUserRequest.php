<?php

namespace App\Http\Requests;

use App\Models\roles;
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

        $requiredOutletRoles = roles::query()
            ->whereIn('name', [
                'pegawai',
                'spv',
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
                Rule::unique('users', 'username')
                    ->ignore($userId),
            ],

            'password' => 'nullable|string|min:8',

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
            'nip' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_masuk' => 'nullable|date',
            'tamatan' => 'nullable|string|max:255',
            'domisili' => 'nullable|string|max:255',
            'tipe_bpjs' => 'nullable|string|max:100',
            'golongan' => 'nullable|string|max:100',
            'unit_id' => 'nullable|exists:units,id',
            'jabatan_id' => 'nullable|exists:jabatans,id',
            'alamat' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'outlet_id.required' => 'Outlet wajib dipilih untuk role ini.',
        ];
    }
}