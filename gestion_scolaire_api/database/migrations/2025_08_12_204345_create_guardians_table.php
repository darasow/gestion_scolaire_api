<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 200);
            $table->text('adresse')->nullable();
            $table->string('telephone1', 20);
            $table->string('telephone2', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('profession', 100)->nullable();
            $table->string('lieu_travail', 200)->nullable();
            $table->timestamps();

            $table->index('telephone1');
            $table->index('full_name');

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
