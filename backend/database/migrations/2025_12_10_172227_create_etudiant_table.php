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
        Schema::create('etudiant', function (Blueprint $table) {
            $table->integer('idEtudiant', true);
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->integer('idF')->nullable()->index('idx_etudiant_filiere');
            $table->string('niveau', 50);
            $table->string('CIN', 20)->unique('cin');
            $table->string('numApogee', 20)->index('idx_etudiant_apogee');
            $table->string('emailInstitu', 100)->unique('emailinstitu');
            $table->date('dateNaissance')->nullable();
            $table->string('lieuNaissance')->nullable();

            $table->index(['CIN'], 'idx_etudiant_cin');
            $table->unique(['numApogee'], 'numapogee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiant');
    }
};
