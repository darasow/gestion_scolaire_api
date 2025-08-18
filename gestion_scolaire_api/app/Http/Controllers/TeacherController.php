<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Liste tous les enseignants
     */
    public function index()
    {
        $teachers = Teacher::with(['auteur', 'modifier'])->get();

        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    /**
     * Crée un nouvel enseignant
     */
   public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:200',
            'email' => 'nullable|email|unique:teachers,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'date_naissance' => 'nullable|date',
            'date_embauche' => 'nullable|date',
            'numero_cnss' => 'nullable|string|max:50',
            'diplomes' => 'nullable|string',
            'specialite' => 'nullable|string|max:255',
            'statut' => 'nullable|in:actif,inactif,suspendu',
            'salaire_base' => 'nullable|numeric',
        ]);

        // Upload des fichiers
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('teachers/photos', 'public');
        }
        if ($request->hasFile('piece_jointe')) {
            $validated['piece_jointe'] = $request->file('piece_jointe')->store('teachers/pieces', 'public');
        }

        $validated['created_by'] = Auth::id();
        $teacher = Teacher::create($validated);

        return response()->json(['success' => true, 'data' => $teacher], 201);
    }

public function update(Request $request, $id)
{
    $teacher = Teacher::findOrFail($id);

    $validated = $request->validate([
        'full_name' => 'required|string|max:200',
        'email' => 'nullable|email|unique:teachers,email,' . $teacher->id,
        'telephone' => 'required|string|max:20',
        'adresse' => 'nullable|string',
        'photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'date_naissance' => 'nullable|date',
        'date_embauche' => 'nullable|date',
        'numero_cnss' => 'nullable|string|max:50',
        'diplomes' => 'nullable|string',
        'specialites' => 'nullable|string|max:255',
        'statut' => 'nullable|in:actif,inactif,suspendu',
        'salaire_base' => 'nullable|numeric',
    ]);
     // Si nouvelle photo => supprimer l’ancienne puis enregistrer la nouvelle
    if ($request->hasFile('photo')) {
        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }
        $validated['photo'] = $request->file('photo')->store('teachers/photos', 'public');
    }

    // Si nouvelle pièce jointe => supprimer l’ancienne puis enregistrer la nouvelle
    if ($request->hasFile('piece_jointe')) {
        if ($teacher->piece_jointe && Storage::disk('public')->exists($teacher->piece_jointe)) {
            Storage::disk('public')->delete($teacher->piece_jointe);
        }
        $validated['piece_jointe'] = $request->file('piece_jointe')->store('teachers/pieces', 'public');
    }

    $validated['modified_by'] = Auth::id();
    $teacher->update($validated);

    return response()->json(['success' => true, 'data' => $teacher]);
}

    /**
     * Affiche un enseignant
     */
    public function show($id)
    {
        $teacher = Teacher::with(['auteur', 'modifier'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $teacher
        ]);
    }

    /**
   
     * Supprime un enseignant
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        // Supprimer les fichiers existants
        if ($teacher->photo) {
            $photoPath = str_replace('storage/', '', $teacher->photo); // enlever "storage/" si nécessaire
            Storage::disk('public')->delete($photoPath);
        }

        if ($teacher->piece_jointe) {
            $piecePath = str_replace('storage/', '', $teacher->piece_jointe);
            Storage::disk('public')->delete($piecePath);
        }

        $teacher->delete();

        return response()->json(['message' => 'Enseignant supprimé avec succès.']);
    }


}