<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory;

    protected $table = 'cycles';

    protected $fillable = [
        'designation',
        'status',
        'created_by',
        'modified_by'
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
