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
        Schema::create('concerne', function (Blueprint $table) {
            $table->integer('idConcerne', true);
            $table->integer('idContient')->index('idx_concerne_contient');
            $table->integer('idEtudiant')->index('idx_concerne_etudiant');
            $table->string('annee', 20);
            $table->decimal('note', 5)->nullable();
            $table->date('dateExamen')->nullable();
            $table->enum('session', ['Normale', 'Rattrapage'])->nullable()->default('Normale');

            $table->unique(['idEtudiant', 'idContient', 'annee', 'session'], 'etudiant_contient_annee_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concerne');
    }
};
