<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function Unit()
    {
        $data = Unit::where('is_active', true)->get();
        return response()->json($data);
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:Units,name',
            'description' => 'nullable|string',
        ]);

        $Unit = Unit::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Unit created successfully',
            'data' => $Unit
        ]);
    }

    public function updateUnit(Request $request, $id)
    {
        $Unit = Unit::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:Units,name,' . $Unit->id,
            'description' => 'nullable|string',
        ]);

        $Unit->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Unit updated successfully',
            'data' => $Unit
        ]);
    }

    public function destroyUnit($id)
    {
        $Unit = Unit::findOrFail($id);
        $Unit->update(['is_active' => false]);

        return response()->json([
            'message' => 'Unit deleted successfully'
        ]);
    }
}
