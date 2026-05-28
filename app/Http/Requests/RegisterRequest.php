<?php

namespace App\Http\Requests;

use App\Models\roles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        $requiredOutletRoles = roles::query()
            ->whereIn('name', [
                'pegawai',
                'spv',
                'manager',
            ])
            ->pluck('id')
            ->toArray();

        return [

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
                'unique:user_requests,username',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'password_confirmation' => [
                'required',
                'string',
                'min:8',
                'same:password',
            ],


            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            'nip' => [
                'required',
                'string',
                'max:100',
                'unique:profiles,nip',
                'unique:user_requests,nip',
            ],

            'alamat' => [
                'required',
                'string',
            ],

            'tamatan' => [
                'required',
                'string',
                'max:255',
            ],

            'jenis_kelamin' => [
                'required',
                'in:L,P',
            ],

            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
            ],


            'domisili' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Nama wajib diisi.',

            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            'role.required' => 'Role wajib dipilih.',

            'outlet_id.required' => 'Outlet wajib dipilih untuk role ini.',

            'nip.unique' => 'NIP sudah digunakan.',

            'tanggal_lahir.before' => 'Tanggal lahir tidak valid.',
        ];
    }
}