<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
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

            // outlet pivot
            if (!empty($validated['outlet_id'])) {

                $user->outlets()->sync([
                    $validated['outlet_id']
                ]);
            }

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
                'unit_id' => $validated['unit_id'],
                'jabatan_id' => $validated['jabatan_id'],
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
            'outlets'
        ])->where('is_active', true)->get();

        $data = $users->map(function ($item, $index) {

            return [

                'id' => $item->id,

                'no' => $index + 1,

                'name' => $item->name,

                'username' => $item->username,

                'role' => $item->role?->name ?? '-',

                'role_id' => $item->role_id,

                'outlet' => $item->outlets
                    ->pluck('name')
                    ->join(', ') ?: '-',

                'outlet_id' => $item->outlets
                    ->pluck('id')
                    ->first(),

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

            // OUTLET
            if (!empty($validated['outlet_id'])) {

                $user->outlets()->sync([
                    $validated['outlet_id']
                ]);

            } else {

                $user->outlets()->detach();
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
                    'tipe_bpjs' => $validated['tipe_bpjs'] ?? null,
                    'unit_id' => $validated['unit_id'] ?? null,
                    'jabatan_id' => $validated['jabatan_id'] ?? null,
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
