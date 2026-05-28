<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function signin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $key = Str::lower($request->username) . '|' . $request->ip();

        // cek terlalu banyak request
        if (RateLimiter::tooManyAttempts($key, 5)) {

            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
            ], 429);
        }

        // tambah hit
        RateLimiter::hit($key, 60);
        $credentials = $request->only(
            'username',
            'password'
        );

        if (!Auth::attempt($credentials)) {

            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        $user = User::with([
            'role.menus.children'
        ])->find(Auth::id());

        /*
        |--------------------------------------------------------------------------
        | Store Session
        |--------------------------------------------------------------------------
        */

        session([
            'user_data' => $user,

            'sidebar_menus' => $user->role
                ->menus
                ->whereNull('parent_id')
                ->sortBy('sort')
                ->values(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */
        RateLimiter::clear($key);
        return response()->json([
            'success' => true,

            'redirect' => route('dashboard')
        ]);
    }

    public function createUser(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validated();
            UserRequest::create([
                ...$validated,
                'password' => bcrypt(
                    $validated['password']
                ),

                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pendaftaran berhasil, menunggu persetujuan admin.'
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
