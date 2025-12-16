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
        if (!Schema::hasColumn('demande', 'num_demande')) {
            // Ajouter la colonne num_demande à la table demande
            Schema::table('demande', function (Blueprint $table) {
                $table->string('num_demande', 50)->nullable()->after('idDemande');
            });

            // Mettre à jour les valeurs existantes avec des numéros uniques
            DB::statement("UPDATE demande SET num_demande = CONCAT('DEM-', YEAR(datesoumission), '-', LPAD(idDemande, 6, '0')) WHERE num_demande IS NULL OR num_demande = ''");

            // Rendre la colonne non nullable et unique
            Schema::table('demande', function (Blueprint $table) {
                $table->string('num_demande', 50)->nullable(false)->change();
                $table->unique('num_demande');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande', function (Blueprint $table) {
            $table->dropUnique('demande_num_demande_unique');
            $table->dropColumn('num_demande');
        });
    }
};
