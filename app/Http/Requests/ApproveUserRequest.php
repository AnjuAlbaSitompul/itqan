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

            'tipe_bpjs' => [
                'required',
                'in:Kesehatan,Ketenagakerjaan',
            ],

            'golongan' => [
                'required',
                'string',
                'max:100',
            ],
            'due_date' => [
                'nullable',
                'date',
            ],
            'status' => [
                'required',
                'in:magang,contract,permanent',
            ],
        ];

    }

    public function messages(): array
    {
        return [
            'role.required' => 'Role harus diisi.',
            'role.exists' => 'Role tidak valid.',
            'golongan.required' => 'Golongan harus diisi.',
            'golongan.string' => 'Golongan harus berupa string.',
            'golongan.max' => 'Golongan tidak boleh lebih dari 100 karakter.',
            'tipe_bpjs.required' => 'Tipe BPJS harus diisi.',
            'tipe_bpjs.in' => 'Tipe BPJS harus salah satu dari: Kesehatan atau Ketenagakerjaan.',

        ];
    }
}
