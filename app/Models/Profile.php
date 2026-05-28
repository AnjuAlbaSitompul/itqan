<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'alamat',
        'tamatan',
        'jenis_kelamin',
        'tanggal_lahir',
        'tanggal_masuk',
        'domisili',
        'tipe_bpjs',
        'unit_id',
        'jabatan_id',
        'golongan',
        'created_by',
        'updated_by',
    ];
}
