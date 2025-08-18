<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'designation',
        'start_date',
        'end_date',
        'is_active',
        'status',
        'created_by',
        'modified_by',
    ];

    // Relation vers l'utilisateur qui a créé l'année
    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation vers l'utilisateur qui a modifié l'année
    public function modifier()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
