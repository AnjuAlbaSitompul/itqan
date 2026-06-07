<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApproveUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $requiredOutletRoles = Role::query()
            ->whereIn('name', [
                'spv',
                'pegawai',
                'manager',
            ])
            ->pluck('id')
            ->toArray();
        return [
            'role_id' => [
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

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'jabatan_id' => [
                'required',
                'exists:jabatans,id',
            ],

            'tipe_bpjs' => [
                'required',
                'in:Kesehatan,Ketenagakerjaan',
            ],

            'golongan' => [
                'required',
                'string',
                'max:100',
            ],
        ];

    }

    public function messages(): array
    {
        return [
            'role.required' => 'Role harus diisi.',
            'role.exists' => 'Role tidak valid.',
            'outlet_id.required' => 'Outlet harus diisi untuk role tertentu.',
            'outlet_id.exists' => 'Outlet tidak valid.',
            'unit_id.required' => 'Unit harus diisi.',
            'unit_id.exists' => 'Unit tidak valid.',
            'jabatan_id.required' => 'Jabatan harus diisi.',
            'jabatan_id.exists' => 'Jabatan tidak valid.',
        ];
    }
}
