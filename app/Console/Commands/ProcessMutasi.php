<?php

namespace App\Console\Commands;

use App\Models\Mutasi;
use App\Models\OrganizationalUnit;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('mutasi:process')]
#[Description('Proses mutasi yang sudah disetujui dan mencapai tanggal efektif')]
class ProcessMutasi extends Command
{

    public function handle()
    {
        $mutasis = Mutasi::where('status', 'approved')
            ->whereDate('effective_date', Carbon::today())
            ->get();


        if ($mutasis->isEmpty()) {
            $this->info("Tidak ada mutasi yang perlu diproses hari ini.");
            return;
        }

        foreach ($mutasis as $mutasi) {
            DB::transaction(function () use ($mutasi) {
                $user = $mutasi->user;
                $organizationalUnit = OrganizationalUnit::find($mutasi->to_id);

                if (!$organizationalUnit) {
                    $this->error("Organizational Unit dengan ID {$mutasi->to_id} tidak ditemukan. Mutasi untuk User ID {$mutasi->user_id} tidak dapat diproses.");
                    return;
                }

                if (!$user)
                    return;

                $user->update([
                    'role_id' => $mutasi->role_id ?? $user->role_id,
                ]);

                // Update Profile
                $user->profile()->update([
                    'organizational_unit_id' => $mutasi->to_id,
                    'jabatan_id' => $mutasi->jabatan_id ?? $user->profile->jabatan_id,
                    'is_head' => $mutasi->is_head ?? $user->profile->is_head,
                ]);

                // Update OwnProfile
                $user->ownProfile()->update([
                    'golongan' => $mutasi?->golongan ?? $user->ownProfile?->golongan,
                ]);


                $organizationalUnit->update([
                    'man_power' => $organizationalUnit->man_power + 1,
                ]);
                // Tandai mutasi sebagai 'completed' agar tidak terproses berulang kali
                $mutasi->update(['status' => 'completed']);
            });

            $this->info("Mutasi untuk User ID {$mutasi->user_id} berhasil diproses.");
        }
    }
}
