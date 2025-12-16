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
        Schema::table('reclamation', function (Blueprint $table) {
            // Ajouter la colonne idDemande
            $table->integer('idDemande')->nullable()->after('idAdmin');
            
            // Ajouter l'index pour optimiser les performances
            $table->index('idDemande', 'idx_reclamation_demande');
            
            // Ajouter la contrainte de clé étrangère
            $table->foreign('idDemande', 'fk_reclamation_demande')
                  ->references('idDemande')
                  ->on('demande')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamation', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère
            $table->dropForeign('fk_reclamation_demande');
            
            // Supprimer l'index
            $table->dropIndex('idx_reclamation_demande');
            
            // Supprimer la colonne
            $table->dropColumn('idDemande');
        });
    }
};
