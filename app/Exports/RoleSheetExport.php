<?php

namespace App\Exports;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class RoleSheetExport implements FromArray, WithTitle
{
    public function title(): string
    {
        return 'Roles';
    }

    public function array(): array
    {
        $rows = [
            ['role', 'keterangan']
        ];

        $descriptions = [
            'pegawai' => 'Karyawan operasional',
            'spv' => 'Supervisor yang membawahi pegawai',
            'manager' => 'Manager yang membawahi supervisor',
            'admin' => 'Administrator sistem dan master data',
            'admin_approve' => 'Administrator dengan hak approval transaksi dan dokumen',
            'owner' => 'Pemilik perusahaan dengan akses penuh ke seluruh sistem',
        ];

        foreach (Role::orderBy('id')->get() as $role) {

            $rows[] = [
                $role->name,
                $descriptions[$role->name] ?? '-'
            ];

        }

        return $rows;
    }
}