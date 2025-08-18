<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'piece_jointe',
        'matricule',
        'genre',
        'adresse',
        'lieu_naissance',
        'nationalite',
        'telephone',
        'email'
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    // Optionnel : Accessor pour le nom complet
    public function getNomCompletAttribute()
    {
        return $this->prenom.' '.$this->nom;
    }


       /**
     * Relation many-to-many avec Guardian
     */
    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
                    ->withPivot('relation')
                    ->withTimestamps();
    }
}