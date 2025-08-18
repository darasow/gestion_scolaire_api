<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    public function index()
    {
        $cycles = Cycle::all();
        return response()->json([
            'success' => true,
            'data' => $cycles
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'designation' => 'required|string|unique:cycles,designation',
            'status' => 'nullable|boolean',
            'created_by' => 'nullable|exists:users,id',
            'modified_by' => 'nullable|exists:users,id',
        ]);

        $cycle = Cycle::create($data);

        return response()->json([
            'success' => true,
            'data' => $cycle
        ], 201);
    }

    public function show(Cycle $cycle)
    {
        return response()->json([
            'success' => true,
            'data' => $cycle
        ]);
    }

    public function update(Request $request, $id)
    {
         $cycle = Cycle::findOrFail($id);

        $validated = $request->validate([
            'designation' => 'required|string|unique:cycles,designation,' . $cycle->id . ',id',
            'status' => 'nullable|string|in:actif,inactif',
            'modified_by' => 'nullable|exists:users,id',
        ]);

        $cycle->update($validated);

        return response()->json([
            'success' => true,
            'data' => $cycle
        ]);
    }


    public function destroy(Cycle $cycle)
    {
        $cycle->delete();
        return response()->json([
            'success' => true,
            'data' => null
        ], 204);
    }
}
