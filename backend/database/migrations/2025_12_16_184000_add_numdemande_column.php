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
        // Ajouter la colonne numDemande à la table demande
        Schema::table('demande', function (Blueprint $table) {
            $table->string('numDemande', 50)->nullable()->after('idDemande');
        });

        // Mettre à jour les valeurs existantes avec des numéros uniques
        DB::statement("UPDATE demande SET numDemande = CONCAT('DEM-', YEAR(datesoumission), '-', LPAD(idDemande, 6, '0')) WHERE numDemande IS NULL OR numDemande = ''");

        // Rendre la colonne non nullable et unique
        Schema::table('demande', function (Blueprint $table) {
            $table->string('numDemande', 50)->nullable(false)->change();
            $table->unique('numDemande');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande', function (Blueprint $table) {
            $table->dropUnique('demande_numdemande_unique');
            $table->dropColumn('numDemande');
        });
    }
};
