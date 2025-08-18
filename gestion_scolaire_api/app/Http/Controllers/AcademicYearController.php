<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::all();
        return response()->json([
            'success' => true,
            'data' => $years
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation' => 'required|string|unique:academic_years,designation',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'status' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $year = AcademicYear::create($validated);

        return response()->json([
            'success' => true,
            'data' => $year
        ], 201);
    }

    public function show($id)
    {
        $year = AcademicYear::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $year
        ]);
    }

    public function update(Request $request, $id)
    {
        $year = AcademicYear::findOrFail($id);

        $validated = $request->validate([
            'designation' => 'string|unique:academic_years,designation,' . $id,
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'is_active' => 'boolean',
            'status' => 'nullable|string',
        ]);

        $validated['modified_by'] = auth()->id();

        $year->update($validated);

        return response()->json([
            'success' => true,
            'data' => $year
        ]);
    }

    public function destroy($id)
    {
        $year = AcademicYear::findOrFail($id);
        $year->delete();

        return response()->json([
            'success' => true,
            'message' => 'Academic year deleted'
        ]);
    }
}
