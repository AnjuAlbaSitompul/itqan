<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Outlet;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function users()
    {
        $roles = Role::select('id', 'name')->get();
        $outlet = Outlet::where('is_active', true)->select('id', 'name')->get();
        $units = Unit::where('is_active', true)->select('id', 'name')->get();
        $jabatans = Jabatan::where('is_active', true)->select('id', 'name')->get();
        return view('master.users.index', compact('roles', 'outlet', 'units', 'jabatans'));
    }

    public function outlet()
    {
        return view('master.outlet.index');
    }

    public function tpi()
    {
        return view('master.tpi.index');
    }
    public function idp()
    {
        return view('master.idp.index');
    }

    public function jabatan()
    {
        return view('master.jabatan.index');
    }

    public function unit()
    {
        return view('master.unit.index');
    }
}
