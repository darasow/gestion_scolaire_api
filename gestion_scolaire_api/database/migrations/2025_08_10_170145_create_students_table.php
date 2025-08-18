<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->date('date_naissance');
            $table->string('piece_jointe', 255)->nullable();
            $table->string('matricule', 20)->unique();
            $table->enum('genre', ['M', 'F']);
            $table->text('adresse')->nullable();
            $table->string('lieu_naissance', 100)->nullable();
            $table->string('nationalite', 50)->default('Guinéenne');
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
           $table->string('status', 100)->nullable()->default('actif');
            $table->timestamps();

            // Index
            $table->index('matricule', 'idx_matricule');
            $table->index(['nom', 'prenom'], 'idx_nom_prenom');
            $table->index('genre', 'idx_genre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
