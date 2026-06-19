<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // WAJIB DIIMPOR UNTUK MENGHAPUS FILE
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('role', 'ownProfile', 'profile.jabatan', 'profile.organizationalUnit');

        $joggingCount = $user->joggingTracks()->sum('distance_km');
        $prayerCount = $user->prayerReports()->count();
        $bookLogs = $user->bookLogs()->where('status', 'approved')->count();

        return view('profile.me.index', compact('user', 'joggingCount', 'prayerCount', 'bookLogs'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'domisili' => 'nullable|string',
            'tamatan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'tipe_bpjs' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'background' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        try {
            // 1. Update Nama User di tabel users
            $user->update([
                'name' => $request->name
            ]);

            // 2. Siapkan data untuk tabel profiles
            $profileData = $request->only([
                'nip',
                'alamat',
                'domisili',
                'tamatan',
                'jenis_kelamin',
                'tanggal_lahir',
                'tipe_bpjs'
            ]);

            // 3. Handling Upload Foto Profil (Avatar) & Hapus File Lama
            if ($request->hasFile('avatar')) {
                // Cek jika user sudah punya avatar lama
                if ($user->ownProfile && $user->ownProfile->avatar) {
                    // Pastikan file fisiknya ada di storage sebelum dihapus
                    if (Storage::disk('public')->exists($user->ownProfile->avatar)) {
                        Storage::disk('public')->delete($user->ownProfile->avatar);
                    }
                }
                // Simpan file baru
                $profileData['avatar'] = $request->file('avatar')->store('profiles/avatars', 'public');
            }

            // 4. Handling Upload Foto Sampul (Background) & Hapus File Lama
            if ($request->hasFile('background')) {
                // Cek jika user sudah punya background lama
                if ($user->ownProfile && $user->ownProfile->background) {
                    // Pastikan file fisiknya ada di storage sebelum dihapus
                    if (Storage::disk('public')->exists($user->ownProfile->background)) {
                        Storage::disk('public')->delete($user->ownProfile->background);
                    }
                }
                // Simpan file baru
                $profileData['background'] = $request->file('background')->store('profiles/backgrounds', 'public');
            }

            $profileData['updated_by'] = $user->id;

            // 5. Update atau Create data ownProfile
            $user->ownProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }
}