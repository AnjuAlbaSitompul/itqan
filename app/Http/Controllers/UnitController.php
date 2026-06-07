<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class UnitController extends Controller
{
    public function Unit()
    {
        $data = Unit::where('is_active', true)->with(['subunit', 'outlet'])->get();
        return response()->json($data);
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:units,name',
            'description' => 'nullable|string',
            'outlet_id' => 'nullable|exists:outlets,id',

            'subUnits' => 'required|array|min:1',
            'subUnits.*.name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $unit = Unit::create([
                'name' => $request->name,
                'description' => $request->description,
                'outlet_id' => $request->outlet_id,
            ]);

            foreach ($request->subUnits as $subUnit) {

                $unit->subunit()->create([
                    'name' => $subUnit['name'],
                ]);

            }

            DB::commit();

            return response()->json([
                'message' => 'Unit created successfully',
                'data' => $unit->load('subunit')
            ], 201);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create Unit',
                'error' => $th->getMessage()
            ], 500);

        }
    }

    public function updateUnit(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:units,name,' . $unit->id,
            'description' => 'nullable|string',
            'outlet_id' => 'nullable|exists:outlets,id',

            'subUnits' => 'required|array|min:1',
            'subUnits.*.name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $unit->update([
                'name' => $request->name,
                'description' => $request->description,
                'outlet_id' => $request->outlet_id,
            ]);

            // Hapus sub unit lama
            $unit->subunit()->delete();

            $unit->subunit()->createMany(
                collect($request->subUnits)
                    ->map(fn($item) => [
                        'name' => $item['name']
                    ])
                    ->toArray()
            );

            DB::commit();

            return response()->json([
                'message' => 'Unit updated successfully',
                'data' => $unit->fresh()->load('subunit')
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update Unit',
                'error' => $th->getMessage()
            ], 500);

        }
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
