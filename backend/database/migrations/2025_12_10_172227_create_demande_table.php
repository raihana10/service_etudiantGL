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
        Schema::create('demande', function (Blueprint $table) {
            $table->integer('idDemande', true);
            $table->integer('idEtudiant')->index('idx_demande_etudiant');
            $table->integer('idAdmin')->nullable()->index('idx_demande_admin');
            $table->timestamp('datesoumission')->useCurrent()->index('idx_demande_date');
            $table->enum('typeDoc', ['ConventionStage', 'ReleveNote', 'AttestationReussite', 'AttestationScolarite'])->index('idx_demande_type');
            $table->enum('statut', ['En attente', 'Validée', 'Refusée'])->nullable()->default('En attente')->index('idx_demande_statut');
            $table->text('motif_refus')->nullable();
            $table->timestamp('date_traitement')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande');
    }
};
