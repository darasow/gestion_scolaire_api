<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Guardian;
use Illuminate\Http\Request;

class StudentGuardianController extends Controller
{
    /**
     * Liste tous les tuteurs d'un étudiant
     */
    public function index(Student $student)
    {
        $guardians = $student->guardians()->get();
       
        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    public function attach(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'guardian_id' => 'required|exists:guardians,id',
        'relation' => 'nullable|string|max:50'
    ]);

    $student = Student::findOrFail($request->student_id);
    $student->guardians()->syncWithoutDetaching([
        $request->guardian_id => ['relation' => $request->relation ?? '']
    ]);

    return response()->json([
        'message' => 'Tuteur ajouté avec succès',
        'student_id' => $student->id,
        'guardian_id' => $request->guardian_id
    ]);
}

public function detach(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'guardian_id' => 'required|exists:guardians,id'
    ]);

    $student = Student::findOrFail($request->student_id);
    $student->guardians()->detach($request->guardian_id);

    return response()->json([
        'message' => 'Tuteur retiré avec succès',
        'student_id' => $student->id,
        'guardian_id' => $request->guardian_id
    ]);
}


public function getStudents(Guardian $guardian)
{
    // Récupérer tous les élèves liés à ce tuteur avec la relation
    $students = $guardian->students()->withPivot('relation')->get();

    // Ajouter la relation dans chaque élève pour le frontend
    $studentsWithRelation = $students->map(function ($student) {
        return [
            'id' => $student->id,
            'nom' => $student->nom,
            'relation' => $student->pivot->relation ?? ''
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $studentsWithRelation
    ]);
}

public function updateRelation(Request $request, Student $student, Guardian $guardian)
{
    $request->validate([
        'relation' => 'required|string|max:50',
    ]);

    // Vérifie que le tuteur est déjà attaché
    if (!$student->guardians()->where('guardian_id', $guardian->id)->exists()) {
        return response()->json(['message' => 'Tuteur non attaché'], 404);
    }

    // Met à jour la relation
    $student->guardians()->updateExistingPivot($guardian->id, [
        'relation' => $request->relation,
    ]);

    return response()->json([
        'message' => 'Relation mise à jour avec succès',
        'student_id' => $student->id,
        'guardian_id' => $guardian->id,
        'relation' => $request->relation,
    ]);
}





}
