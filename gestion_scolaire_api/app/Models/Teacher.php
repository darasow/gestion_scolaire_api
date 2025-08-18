<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'full_name',
        'email',
        'telephone',
        'adresse',
        'photo',
        'date_naissance',
        'date_embauche',
        'numero_cnss',
        'diplomes',
        'specialites',
        'statut',
        'salaire_base',
        'created_by',
        'modified_by',
        'piece_jointe',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_embauche' => 'date',
        'salaire_base' => 'decimal:2',
    ];

    // Créateur de l'enregistrement
    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Dernière modification
    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
