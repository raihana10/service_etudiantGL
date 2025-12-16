<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la colonne numDemande existe déjà et n'est pas unique
        if (Schema::hasColumn('demande', 'numDemande') && !Schema::hasColumn('demande', 'numDemande_unique')) {
            // Mettre à jour les valeurs existantes avec des numéros uniques
            DB::statement("UPDATE demande SET numDemande = CONCAT('DEM-', YEAR(datesoumission), '-', LPAD(idDemande, 6, '0')) WHERE numDemande IS NULL OR numDemande = ''");
            
            // Ajouter la contrainte unique
            Schema::table('demande', function (Blueprint $table) {
                $table->unique('numDemande');
            });
        }

        // S'assurer que la colonne priorite est supprimée de reclamation
        if (Schema::hasColumn('reclamation', 'priorite')) {
            Schema::table('reclamation', function (Blueprint $table) {
                $table->dropColumn('priorite');
            });
        }

        // S'assurer que la colonne anneeObtentionDiplome existe dans etudiant
        if (!Schema::hasColumn('etudiant', 'anneeObtentionDiplome')) {
            Schema::table('etudiant', function (Blueprint $table) {
                $table->year('anneeObtentionDiplome')->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la contrainte unique sur numDemande
        Schema::table('demande', function (Blueprint $table) {
            $table->dropUnique('demande_numdemande_unique');
        });

        // Restaurer la colonne priorite dans la table reclamation
        Schema::table('reclamation', function (Blueprint $table) {
            $table->enum('priorite', ['Haute', 'Normale', 'Basse'])->nullable()->default('Normale')->after('statut');
        });

        // Supprimer anneeObtentionDiplome de la table etudiant
        Schema::table('etudiant', function (Blueprint $table) {
            $table->dropColumn('anneeObtentionDiplome');
        });
    }
};
