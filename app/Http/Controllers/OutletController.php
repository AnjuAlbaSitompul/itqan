<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlet = Outlet::where('is_active', true)->get();
        return response()->json($outlet);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'alamat' => 'nullable|string',
        ]);

        $outlet = Outlet::create($validated);
        return response()->json([
            'message' => 'Outlet created successfully',
            'data' => $outlet
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);
        $validated = $request->validate([
            'name' => 'string',
            'alamat' => 'string',
        ]);

        $outlet->update($validated);
        return response()->json([
            'message' => 'Outlet updated successfully',
            'data' => $outlet
        ]);
    }

    public function destroy($id)
    {
        $outlet = Outlet::findOrFail($id);
        $outlet->is_active = false;
        $outlet->save();
        return response()->json([
            'message' => 'Outlet deactivated successfully'
        ], 200);
    }
}
