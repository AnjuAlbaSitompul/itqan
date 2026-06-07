<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserSheetExport implements FromArray, WithHeadings, WithTitle
{
    public function title(): string
    {
        return 'Users';
    }

    public function headings(): array
    {
        return [
            'name',
            'username',
            'password',
            'role',
            'nip',
            'alamat',
            'tamatan',
            'jenis_kelamin',
            'tanggal_lahir',
            'tanggal_masuk',
            'domisili',
            'tipe_bpjs',
            'golongan',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Anju Sitompul',
                'anju',
                'password123',
                'pegawai',
                '20260001',
                'Jl. Gatot Subroto No.1',
                'S1',
                'L',
                '1999-05-10',
                '2025-01-01',
                'Medan',
                'Kesehatan',
                'IIIA',
            ]
        ];
    }
}