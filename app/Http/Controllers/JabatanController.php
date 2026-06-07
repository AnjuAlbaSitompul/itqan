<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function jabatan()
    {
        $data = Jabatan::where('is_active', true)->get();
        return response()->json($data);
    }

    public function storeJabatan(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:jabatans,name',
            'level' => 'required|integer',
        ]);

        $jabatan = Jabatan::create([
            'name' => $request->name,
            'level' => $request->level,
        ]);

        return response()->json([
            'message' => 'Jabatan created successfully',
            'data' => $jabatan
        ]);
    }

    public function updateJabatan(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:jabatans,name,' . $jabatan->id,
            'level' => 'required|integer',
        ]);

        $jabatan->update([
            'name' => $request->name,
            'level' => $request->level,
        ]);

        return response()->json([
            'message' => 'Jabatan updated successfully',
            'data' => $jabatan
        ]);
    }

    public function destroyJabatan($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update(['is_active' => false]);

        return response()->json([
            'message' => 'Jabatan deleted successfully'
        ]);
    }

}
