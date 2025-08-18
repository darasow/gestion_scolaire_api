<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    /**
     * Liste tous les tuteurs
     */
    public function index()
    {
        try {
            $guardians = Guardian::all();
            return response()->json([
                'success' => true,
                'data' => $guardians
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Crée un nouveau tuteur
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:200',
            'telephone1' => 'required|string|max:20',
            'telephone2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'adresse' => 'nullable|string',
            'profession' => 'nullable|string|max:100',
            'lieu_travail' => 'nullable|string|max:200',
        ]);

        $guardian = Guardian::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $guardian,
            'status' => 201
        ]);
    }

    /**
     * Affiche un tuteur spécifique
     */
    public function show($id)
    {
        $guardian = Guardian::find($id);
        if (!$guardian) {
            return response()->json(['message' => 'Tuteur non trouvé'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $guardian,
            'status' => 200
        ]);
    }

    /**
     * Met à jour un tuteur
     */
    public function update(Request $request, $id)
    {
        $guardian = Guardian::find($id);
        if (!$guardian) {
            return response()->json(['message' => 'Tuteur non trouvé'], 404);
        }

        $request->validate([
            'full_name' => 'sometimes|required|string|max:200',
            'telephone1' => 'sometimes|required|string|max:20',
            'telephone2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'adresse' => 'nullable|string',
            'profession' => 'nullable|string|max:100',
            'lieu_travail' => 'nullable|string|max:200',
        ]);

        $guardian->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $guardian,
            'status' => 200
        ]);
    }

    /**
     * Supprime un tuteur
     */
    public function destroy($id)
    {
        $guardian = Guardian::find($id);
        if (!$guardian) {
            return response()->json(['message' => 'Tuteur non trouvé'], 404);
        }

        $guardian->delete();

        return response()->json(['message' => 'Tuteur supprimé avec succès']);
    }

    /**
     * Récupère tous les élèves liés à un tuteur
     */
    public function students($guardianId)
    {
        $guardian = Guardian::with('students')->find($guardianId);
        if (!$guardian) {
            return response()->json(['message' => 'Tuteur non trouvé'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $guardian->students
        ]);
    }

    /**
     * Associe un élève à un tuteur
     */
    public function attachStudent(Request $request, $guardianId)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'relation' => 'nullable|string|max:50',
        ]);

        $guardian = Guardian::find($guardianId);
        if (!$guardian) {
            return response()->json(['message' => 'Tuteur non trouvé'], 404);
        }

        $guardian->students()->attach($request->student_id, [
            'relation' => $request->relation
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Élève associé avec succès'
        ]);
    }

    /**
     * Supprime l'association entre un élève et un tuteur
     */
    public function detachStudent($guardianId, $studentId)
    {
        $guardian = Guardian::find($guardianId);
        if (!$guardian) {
            return response()->json(['message' => 'Tuteur non trouvé'], 404);
        }

        $guardian->students()->detach($studentId);

        return response()->json([
            'success' => true,
            'message' => 'Association supprimée avec succès'
        ]);
    }
}
