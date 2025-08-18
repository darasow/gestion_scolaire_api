<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Affiche la liste des étudiants
     */
    public function index()
    {
        $students = Student::orderBy('nom')->orderBy('prenom')->get();
        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Stocke un nouvel étudiant avec gestion de la pièce jointe
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'matricule' => 'required|string|max:20|unique:students',
            'genre' => 'required|in:M,F',
            'adresse' => 'nullable|string',
            'lieu_naissance' => 'nullable|string|max:100',
            'nationalite' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('piece_jointe');

        // Gestion du fichier
        if ($request->hasFile('piece_jointe')) {
            $path = $request->file('piece_jointe')->store('public/students/pieces_jointes');
            $data['piece_jointe'] = Storage::url($path);
        }

        $student = Student::create($data);

        return response()->json([
            'success' => true,
            'data' => $student
        ], 201);
    }

    /**
     * Affiche les détails d'un étudiant
     */
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Met à jour un étudiant
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:100',
            'prenom' => 'sometimes|string|max:100',
            'date_naissance' => 'sometimes|date',
            'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'matricule' => 'sometimes|string|max:20|unique:students,matricule,'.$student->id,
            'genre' => 'sometimes|in:M,F',
            'adresse' => 'nullable|string',
            'lieu_naissance' => 'nullable|string|max:100',
            'nationalite' => 'nullable|string|max:50',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('piece_jointe');

        // Gestion du fichier
        if ($request->hasFile('piece_jointe')) {
            // Supprimer l'ancien fichier s'il existe
            if ($student->piece_jointe) {
                $oldPath = str_replace('/storage', 'public', $student->piece_jointe);
                Storage::delete($oldPath);
            }

            $path = $request->file('piece_jointe')->store('public/students/pieces_jointes');
            $data['piece_jointe'] = Storage::url($path);
        }

        $student->update($data);

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Supprime un étudiant
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant non trouvé'
            ], 404);
        }

        // Supprimer le fichier associé s'il existe
        if ($student->piece_jointe) {
            $path = str_replace('/storage', 'public', $student->piece_jointe);
            Storage::delete($path);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Étudiant supprimé avec succès'
        ]);
    }

    /**
     * Télécharge la pièce jointe
     */
    public function downloadPieceJointe($id)
    {
        $student = Student::find($id);

        if (!$student || !$student->piece_jointe) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier non trouvé'
            ], 404);
        }

        $path = str_replace('/storage', 'public', $student->piece_jointe);
        
        if (!Storage::exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier non trouvé sur le serveur'
            ], 404);
        }

        return Storage::download($path);
    }
}