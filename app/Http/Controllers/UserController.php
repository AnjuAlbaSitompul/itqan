<?php

namespace App\Http\Controllers;

use App\Exports\UserTemplateExport;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{

    public function downloadTemplate()
    {
        return Excel::download(
            new UserTemplateExport(),
            'user_import_template.xlsx'
        );
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

        try {

            $rows = Excel::toArray([], $request->file('file'));

            $users = $rows[0];

            if (count($users) <= 1) {
                throw new \Exception('File tidak memiliki data.');
            }

            unset($users[0]); // hapus header

            $success = 0;

            foreach ($users as $index => $row) {

                $line = $index + 2;

                [
                    $name,
                    $username,
                    $password,
                    $roleName,
                    $nip,
                    $alamat,
                    $tamatan,
                    $jenisKelamin,
                    $tanggalLahir,
                    $tanggalMasuk,
                    $domisili,
                    $tipeBpjs,
                    $golongan
                ] = $row;

                if (
                    empty($name) ||
                    empty($username) ||
                    empty($password) ||
                    empty($roleName) ||
                    empty($nip)
                ) {
                    throw new \Exception("Data tidak lengkap pada baris {$line}");
                }

                $role = Role::whereRaw('LOWER(name) = ?', [
                    strtolower(trim($roleName))
                ])->first();

                if (!$role) {
                    throw new \Exception("Role '{$roleName}' tidak ditemukan pada baris {$line}");
                }

                if (User::where('username', $username)->exists()) {
                    throw new \Exception("Username '{$username}' sudah digunakan pada baris {$line}");
                }

                if (Profile::where('nip', $nip)->exists()) {
                    throw new \Exception("NIP '{$nip}' sudah digunakan pada baris {$line}");
                }

                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'password' => Hash::make($password),
                    'role_id' => $role->id,
                ]);

                Profile::create([
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'alamat' => $alamat,
                    'tamatan' => $tamatan,
                    'jenis_kelamin' => $jenisKelamin,
                    'tanggal_lahir' => $tanggalLahir,
                    'tanggal_masuk' => $tanggalMasuk,
                    'domisili' => $domisili,
                    'tipe_bpjs' => $tipeBpjs,
                    'golongan' => $golongan,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);

                $success++;
            }

            DB::commit();

            return response()->json([
                'message' => "{$success} user berhasil diimport."
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'message' => 'Import gagal',
                'error' => $th->getMessage()
            ], 500);

        }
    }
    public function create(StoreUserRequest $request)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => bcrypt($validated['password']),
                'role_id' => $validated['role'],
            ]);

            // profile
            $user->profile()->create([
                'nip' => $validated['nip'],
                'alamat' => $validated['alamat'],
                'tamatan' => $validated['tamatan'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'domisili' => $validated['domisili'],
                'tipe_bpjs' => $validated['tipe_bpjs'],
                'golongan' => $validated['golongan'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'User created successfully'
            ], 201);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $users = User::with([
            'role',
            'profile'
        ])->where('is_active', true)->get();

        $data = $users->map(function ($item, $index) {

            return [
                'id' => $item->id,
                'no' => $index + 1,
                'name' => $item->name,
                'username' => $item->username,
                'role' => $item->role?->name ?? '-',
                'role_id' => $item->role_id,
                'golongan' => $item->profile?->golongan ?? '-',
                'jenis_kelamin' => $item->profile?->jenis_kelamin ?? '-',
                'tanggal_lahir' => $item->profile?->tanggal_lahir ?? '-',
                'tanggal_masuk' => $item->profile?->tanggal_masuk ?? '-',
                'domisili' => $item->profile?->domisili ?? '-',
                'tipe_bpjs' => $item->profile?->tipe_bpjs ?? '-',
                'tamatan' => $item->profile?->tamatan ?? '-',
                'nip' => $item->profile?->nip ?? '-',
                'alamat' => $item->profile?->alamat ?? '-',
                'action' => '
            <button 
                class="btn btn-sm btn-primary rounded-pill"
                onclick="editUser(' . $item->id . ')"
            >
                Edit
            </button>

            <button 
                class="btn btn-sm btn-danger rounded-pill"
                onclick="deleteUser(' . $item->id . ')"
            >
                Delete
            </button>
        '
            ];
        });

        return response()->json($data);
    }

    public function delete($id)
    {
        try {

            $user = User::findOrFail($id);

            $user->update([
                'is_active' => false
            ]);

            return response()->json([
                'message' => 'User deleted successfully'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => $th->getMessage()
            ], 500);

        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validated();

            $user = User::findOrFail($id);

            // USER
            $user->update([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'role_id' => $validated['role'],
            ]);

            // PASSWORD
            if (!empty($validated['password'])) {
                $user->update([
                    'password' => bcrypt($validated['password']),
                ]);
            }

            // PROFILE
            $user->profile()->updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [

                    'nip' => $validated['nip'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'tamatan' => $validated['tamatan'] ?? null,
                    'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                    'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                    'tanggal_masuk' => $validated['tanggal_masuk'] ?? null,
                    'domisili' => $validated['domisili'] ?? null,
                    'golongan' => $validated['golongan'] ?? null,
                    'updated_by' => Auth::id(),
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'User updated successfully'
            ], 200);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
