<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    protected $fillable = [
        'name',
        'username',
        'password',
        'nip',
        'alamat',
        'tamatan',
        'jenis_kelamin',
        'tanggal_lahir',
        'domisili',
        'tipe_bpjs',
        'status',
    ];
}
