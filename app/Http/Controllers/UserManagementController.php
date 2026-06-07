<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveUserRequest;
use App\Models\Jabatan;
use App\Models\Outlet;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class UserManagementController extends Controller
{

    public function userRequest()
    {
        $outlets = Outlet::where('is_active', true)->select('id', 'name')->get();
        $jabatans = Jabatan::where('is_active', true)->select('id', 'name')->get();
        $units = Unit::where('is_active', true)->select('id', 'name')->get();
        $roles = Role::select('id', 'name')->get();
        return view('user-management.user-request.index', compact('outlets', 'jabatans', 'units', 'roles'));
    }

    public function request()
    {
        $request = UserRequest::where('status', 'pending')->get();
        return response()->json($request);
    }

    public function approveRequest(ApproveUserRequest $request, $id)
    {
        try {

            $validated = $request->validated();

            DB::transaction(function () use ($validated, $id) {

                $userRequest = UserRequest::lockForUpdate()
                    ->findOrFail($id);

                // prevent double approve
                if ($userRequest->status === 'approved') {

                    throw new \Exception(
                        'User request already approved.'
                    );
                }

                // approve request
                $userRequest->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // create user
                $user = User::create([
                    'name' => $userRequest->name,
                    'username' => $userRequest->username,
                    'password' => $userRequest->password,
                    'role_id' => $validated['role_id'],
                ]);

                // outlet pivot
                if (!empty($validated['outlet_id'])) {

                    $user->outlets()->sync([
                        $validated['outlet_id']
                    ]);
                }

                // create profile
                $user->profile()->create([

                    'nip' => $userRequest->nip,
                    'alamat' => $userRequest->alamat,
                    'tamatan' => $userRequest->tamatan,
                    'jenis_kelamin' => $userRequest->jenis_kelamin,
                    'tanggal_lahir' => $userRequest->tanggal_lahir,

                    // optional field
                    'tanggal_masuk' => now(),

                    'domisili' => $userRequest->domisili,

                    'tipe_bpjs' => $validated['tipe_bpjs'],
                    'unit_id' => $validated['unit_id'],
                    'jabatan_id' => $validated['jabatan_id'],
                    'golongan' => $validated['golongan'],

                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            });

            return response()->json([
                'message' => 'User request approved successfully'
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Failed to approve user request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function rejectRequest($id)
    {
        try {

            $userRequest = UserRequest::findOrFail($id);

            // prevent double reject
            if (!$userRequest) {

                return response()->json([
                    'message' => 'User request not found or already rejected.'
                ], 400);
            }

            $userRequest->delete();

            return response()->json([
                'message' => 'User request rejected successfully'
            ], 200);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Failed to reject user request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
