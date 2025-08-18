<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guardian extends Model
{
    protected $fillable = [
        'full_name',
        'adresse',
        'telephone1',
        'telephone2',
        'email',
        'profession',
        'lieu_travail',
        'created_by'
    ];

    /**
     * Relation many-to-many avec Student
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_guardian')
                    ->withPivot('relation')
                    ->withTimestamps();
    }

    /**
     * L'utilisateur qui a créé l'enregistrement
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
