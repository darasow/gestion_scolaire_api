<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 200);
            $table->string('email', 100)->unique()->nullable();
            $table->string('telephone', 20);
            $table->text('adresse')->nullable();
            $table->string('photo', 255)->nullable();
            $table->date('date_naissance')->nullable();
            $table->date('date_embauche')->nullable();
            $table->string('numero_cnss', 50)->nullable();
            $table->text('diplomes')->nullable();
            $table->json('specialites')->nullable();
            $table->string('statut')->nullable();
            $table->decimal('salaire_base', 20, 2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamps();

            $table->index('email', 'idx_email');
            $table->index('statut', 'idx_statut');
            $table->index('full_name', 'idx_full_name');
            $table->index('telephone', 'idx_telephone');

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('modified_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
