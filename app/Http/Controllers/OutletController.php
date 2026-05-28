<?php

namespace App\Http\Controllers;

use App\Models\outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlet = outlet::where('is_active', true)->get();
        return response()->json($outlet);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'alamat' => 'nullable|string',
            'man_power' => 'required|integer|min:0',
        ]);

        $outlet = outlet::create($validated);
        return response()->json([
            'message' => 'Outlet created successfully',
            'data' => $outlet
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $outlet = outlet::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string',
            'alamat' => 'string',
            'man_power' => 'integer|min:0',
        ]);

        $outlet->update($validated);
        return response()->json([
            'message' => 'Outlet updated successfully',
            'data' => $outlet
        ]);
    }

    public function destroy($id)
    {
        $outlet = outlet::findOrFail($id);
        $outlet->is_active = false;
        $outlet->save();
        return response()->json([
            'message' => 'Outlet deactivated successfully'
        ], 200);
    }
}
