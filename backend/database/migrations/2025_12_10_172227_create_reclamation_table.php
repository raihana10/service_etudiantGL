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
        Schema::create('reclamation', function (Blueprint $table) {
            $table->integer('idReclamation', true);
            $table->integer('idEtudiant')->index('idx_reclamation_etudiant');
            $table->integer('idAdmin')->nullable()->index('idx_reclamation_admin');
            $table->text('description');
            $table->enum('statut', ['Nouvelle', 'En cours', 'Résolue'])->nullable()->default('Nouvelle')->index('idx_reclamation_statut');
            $table->enum('priorite', ['Haute', 'Normale', 'Basse'])->nullable()->default('Normale');
            $table->timestamp('datesoumission')->useCurrent();
            $table->timestamp('dateReponse')->nullable();
            $table->text('reponse')->nullable();
            $table->string('sujet', 200);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamation');
    }
};
